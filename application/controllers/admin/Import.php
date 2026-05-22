<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Import Data - Admin
 * Mengelola import data dari Excel/CSV
 * 
 * @package     CodeIgniter
 * @subpackage  Controllers
 * @category    Admin
 */
class Import extends MY_Controller {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        
        // Set role
        $this->role_required = array('admin');
        
        // Load model
        $this->load->model('admin/M_import');
        $this->load->model('admin/M_siswa');
        $this->load->model('admin/M_guru');
        
        // Load library Excel
        $this->load->library('excel_import');
        
        // Set data global
        $this->data['page_title'] = 'Import Data';
        $this->data['active_menu'] = 'import';
    }

    /**
     * Halaman utama import
     * 
     * @return void
     */
    public function index() {
        $this->data['content'] = 'admin/import';
        $this->load->view('templates/template', $this->data);
    }

    /**
     * AJAX: Preview file Excel
     * 
     * @return void
     */
    public function preview() {
        // Cek file upload
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'File tidak valid atau ukuran terlalu besar'
            )));
            return;
        }
        
        $file = $_FILES['file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Validasi ekstensi
        if (!in_array($ext, array('xlsx', 'xls', 'csv'))) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'Format file harus XLSX, XLS, atau CSV'
            )));
            return;
        }
        
        // Validasi ukuran (max 5MB)
        if ($file['size'] > (5 * 1024 * 1024)) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'Ukuran file maksimal 5MB'
            )));
            return;
        }
        
        try {
            // Baca file
            $type = $this->input->post('type'); // 'siswa' atau 'guru'
            $data = $this->excel_import->read_file($file['tmp_name'], $ext, 10); // Preview 10 baris
            
            if (!$data) {
                $this->output->set_status_header(500);
                $this->output->set_content_type('application/json')->set_output(json_encode(array(
                    'status' => FALSE,
                    'message' => 'Gagal membaca file Excel'
                )));
                return;
            }
            
            $headers = array_shift($data);
            
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => TRUE,
                'headers' => $headers,
                'data' => $data,
                'total_rows' => count($data)
            )));
            
        } catch (Exception $e) {
            $this->output->set_status_header(500);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'Error: ' . $e->getMessage()
            )));
        }
    }

    /**
     * AJAX: Proses import data
     * 
     * @return void
     */
    public function proses() {
        // Cek file upload
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'File tidak valid'
            )));
            return;
        }
        
        $file = $_FILES['file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $type = $this->input->post('type'); // 'siswa' atau 'guru'
        
        if (!in_array($type, array('siswa', 'guru'))) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'Tipe import tidak valid'
            )));
            return;
        }
        
        try {
            // Baca semua data
            $data = $this->excel_import->read_file($file['tmp_name'], $ext);
            
            if (!$data || empty($data)) {
                $this->output->set_status_header(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(array(
                    'status' => FALSE,
                    'message' => 'File kosong atau tidak ada data'
                )));
                return;
            }
            
            // Mulai transaction
            $this->db->trans_start();
            
            $success_count = 0;
            $failed_count = 0;
            $duplicate_count = 0;
            $errors = array();
            
            // Skip header (baris pertama sudah di-skip oleh read_file jika ada option)
            foreach ($data as $index => $row) {
                $row_num = $index + 2; // +2 karena skip header dan 1-based
                
                try {
                    if ($type === 'siswa') {
                        // Format: NIS, Nama, JK, TTL, Alamat, Nama Ortu, No HP Ortu
                        if (count($row) < 7) {
                            $failed_count++;
                            $errors[] = "Baris {$row_num}: Kolom tidak lengkap";
                            continue;
                        }
                        
                        $nis = trim($row[0]);
                        $nama = trim($row[1]);
                        $jk = strtoupper(trim($row[2]));
                        $ttl = trim($row[3]);
                        $alamat = trim($row[4]);
                        $nama_ortu = trim($row[5]);
                        $no_hp_ortu = trim($row[6]);
                        
                        // Cek NIS duplikat
                        $check = $this->db->where('nis', $nis)->get('tb_siswa')->row();
                        if ($check) {
                            $duplicate_count++;
                            $errors[] = "Baris {$row_num}: NIS {$nis} sudah ada";
                            continue;
                        }
                        
                        // Insert siswa
                        $result = $this->M_siswa->create_with_user($nis, $nama, $jk, $ttl, $alamat, $nama_ortu, $no_hp_ortu);
                        
                        if ($result) {
                            $success_count++;
                        } else {
                            $failed_count++;
                            $errors[] = "Baris {$row_num}: Gagal insert data";
                        }
                        
                    } elseif ($type === 'guru') {
                        // Format: NIP, Nama, JK, No HP, Alamat
                        if (count($row) < 5) {
                            $failed_count++;
                            $errors[] = "Baris {$row_num}: Kolom tidak lengkap";
                            continue;
                        }
                        
                        $nip = trim($row[0]);
                        $nama = trim($row[1]);
                        $jk = strtoupper(trim($row[2]));
                        $no_hp = trim($row[3]);
                        $alamat = trim($row[4]);
                        
                        // Cek NIP duplikat
                        $check = $this->db->where('nip', $nip)->get('tb_guru')->row();
                        if ($check) {
                            $duplicate_count++;
                            $errors[] = "Baris {$row_num}: NIP {$nip} sudah ada";
                            continue;
                        }
                        
                        // Insert guru
                        $result = $this->M_guru->create_with_user($nip, $nama, $jk, $no_hp, $alamat);
                        
                        if ($result) {
                            $success_count++;
                        } else {
                            $failed_count++;
                            $errors[] = "Baris {$row_num}: Gagal insert data";
                        }
                    }
                    
                } catch (Exception $e) {
                    $failed_count++;
                    $errors[] = "Baris {$row_num}: " . $e->getMessage();
                }
            }
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                $this->output->set_status_header(500);
                $this->output->set_content_type('application/json')->set_output(json_encode(array(
                    'status' => FALSE,
                    'message' => 'Gagal import data. Semua perubahan dibatalkan.'
                )));
                return;
            }
            
            log_aktivitas('import', 'tb_' . $type, null, "Import {$type}: {$success_count} berhasil, {$failed_count} gagal, {$duplicate_count} duplikat");
            
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => TRUE,
                'message' => "Import selesai. Berhasil: {$success_count}, Gagal: {$failed_count}, Duplikat: {$duplicate_count}",
                'success' => $success_count,
                'failed' => $failed_count,
                'duplicate' => $duplicate_count,
                'errors' => array_slice($errors, 0, 10) // Max 10 error messages
            )));
            
        } catch (Exception $e) {
            $this->output->set_status_header(500);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'Error: ' . $e->getMessage()
            )));
        }
    }

    /**
     * Download template Excel
     * 
     * @param string $type Tipe template (siswa/guru)
     * @return void
     */
    public function download_template($type) {
        if (!in_array($type, array('siswa', 'guru'))) {
            show_404();
        }
        
        // Load library Excel template
        $this->load->library('excel_template');
        
        if ($type === 'siswa') {
            $spreadsheet = $this->excel_template->generate_siswa_template();
            $filename = 'template_import_siswa';
        } else {
            $spreadsheet = $this->excel_template->generate_guru_template();
            $filename = 'template_import_guru';
        }
        
        if (!$spreadsheet) {
            show_error('Gagal membuat template Excel. Pastikan library PhpSpreadsheet terinstall.');
            return;
        }
        
        $this->excel_template->download($spreadsheet, $filename);
    }
}
