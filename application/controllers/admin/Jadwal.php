<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Jadwal Pelajaran - Admin
 * Mengelola jadwal pelajaran sekolah
 * 
 * @package     CodeIgniter
 * @subpackage  Controllers
 * @category    Admin
 */
class Jadwal extends MY_Controller {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Load model tahun ajaran jika belum dimuat
        if (!isset($this->M_tahunajaran)) {
            $this->load->model('admin/M_tahunajaran');
        }
        
        // Set role
        $this->role_required = array('admin');
        
        // Load model
        $this->load->model('admin/M_jadwal');
        $this->load->model('admin/M_kelas');
        $this->load->model('admin/M_matapelajaran');
        $this->load->model('admin/M_guru');
        
        // Set data global
        $this->data['page_title'] = 'Jadwal Pelajaran';
        $this->data['active_menu'] = 'jadwal';
    }

    /**
     * Halaman utama jadwal
     * 
     * @return void
     */
    public function index() {
        // Data tahun ajaran aktif sudah tersedia dari MY_Controller
        $this->data['tahun_ajaran'] = $this->tahun_ajaran_aktif;
        
        // Get filter parameters
        $filter_id_kelas = $this->input->get('id_kelas');
        $filter_hari = $this->input->get('hari');
        
        // Load kelas list for filter dropdown
        $this->data['kelas_list'] = $this->M_kelas->get_active_classes();
        
        // Set filter data for view
        $this->data['filter_id_kelas'] = $filter_id_kelas;
        $this->data['filter_hari'] = $filter_hari;
        
        // Build filter info and params
        $filter_info = array();
        $filter_params = array();
        
        if (!empty($filter_id_kelas)) {
            $kelas_data = $this->M_kelas->get_by_id($filter_id_kelas);
            if ($kelas_data) {
                $filter_info[] = 'Kelas: ' . $kelas_data->nama_kelas;
                $filter_params['id_kelas'] = $filter_id_kelas;
            }
        }
        
        if (!empty($filter_hari)) {
            $filter_info[] = 'Hari: ' . $filter_hari;
            $filter_params['hari'] = $filter_hari;
        }
        
        $this->data['filter_info'] = !empty($filter_info) ? implode(', ', $filter_info) : '';
        $this->data['filter_params'] = $filter_params;
        $this->data['show_pdf_button'] = !empty($filter_params);
        
        $this->data['content'] = 'admin/jadwal';
        $this->load->view('templates/template', $this->data);
    }

    /**
     * AJAX: Get list jadwal untuk DataTables
     * 
     * @return void
     */
    public function ajax_list() {
        // Get filter parameters from POST
        $filter_id_kelas = $this->input->post('id_kelas');
        $filter_hari = $this->input->post('hari');
        
        $list = $this->M_jadwal->get_datatables($this->tahun_ajaran_aktif->id, $filter_id_kelas, $filter_hari);
        $data = array();
        
        foreach ($list as $item) {
            $row = new stdClass();
            $row->DT_RowIndex = count($data);
            $row->nama_kelas = $item->nama_kelas;
            $row->hari = $item->hari;
            $row->jam_mulai = $item->jam_mulai;
            $row->jam_selesai = $item->jam_selesai;
            $row->nama_mapel = $item->nama_mapel;
            $row->nama_guru = $item->nama_guru;
            $row->tahun_ajaran = $item->tahun_ajaran;
            
            // Action buttons - same style as guru
            $row->action = '<button class="btn btn-sm btn-warning edit-btn" data-id="' . encrypt_id($item->id) . '"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="' . encrypt_id($item->id) . '"><i class="fas fa-trash"></i></button>';
            
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_jadwal->count_all($this->tahun_ajaran_aktif->id, $filter_id_kelas, $filter_hari),
            "recordsFiltered" => $this->M_jadwal->count_filtered($this->tahun_ajaran_aktif->id, $filter_id_kelas, $filter_hari),
            "data" => $data,
        );
        
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * AJAX: Tambah jadwal baru
     * 
     * @return void
     */
    public function ajax_add() {
        // Debug log
        error_log("=== AJAX ADD JADWAL START ===");
        error_log("POST data: " . print_r($_POST, true));
        
        $data = array(
            'id_kelas' => $this->input->post('id_kelas'),
            'id_guru' => $this->input->post('id_guru'),
            'id_mapel' => $this->input->post('id_mapel'),
            'id_tahun_ajaran' => $this->tahun_ajaran_aktif->id,
            'hari' => $this->input->post('hari'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'jam_selesai' => $this->input->post('jam_selesai'),
            'ruangan' => $this->input->post('ruangan'),
            'status_aktif' => 1
        );
        
        error_log("Data to save: " . print_r($data, true));
        
        // Validate first
        $validation_result = $this->_validate();
        if ($validation_result !== TRUE) {
            error_log("Validation failed!");
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode($validation_result));
            return;
        }
        
        // Cek bentrok jadwal
        if ($this->M_jadwal->check_conflict($data)) {
            error_log("Jadwal bentrok!");
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'Jadwal bentrok dengan jadwal yang sudah ada pada kelas, hari, dan jam yang sama'
            )));
            return;
        }
        
        $insert = $this->M_jadwal->save($data);
        
        error_log("Insert result ID: " . $insert);
        log_aktivitas('insert', 'tb_jadwal', $insert, 'Tambah jadwal pelajaran');
        
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'status' => TRUE,
            'message' => 'Jadwal berhasil ditambahkan'
        )));
    }

    /**
     * AJAX: Edit jadwal
     * 
     * @param string $encrypted_id ID terenkripsi
     * @return void
     */
    public function ajax_edit($encrypted_id) {
        $id = decrypt_id($encrypted_id);
        
        if (!$id) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'ID tidak valid'
            )));
            return;
        }
        
        $data = $this->M_jadwal->get_by_id($id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'status' => TRUE,
            'data' => $data
        )));
    }

    /**
     * AJAX: Update jadwal
     * 
     * @return void
     */
    public function ajax_update() {
        $id = decrypt_id($this->input->post('id'));
        
        if (!$id) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'ID tidak valid'
            )));
            return;
        }
        
        $this->_validate();
        
        $data = array(
            'id_kelas' => $this->input->post('id_kelas'),
            'id_guru' => $this->input->post('id_guru'),
            'id_mapel' => $this->input->post('id_mapel'),
            'hari' => $this->input->post('hari'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'jam_selesai' => $this->input->post('jam_selesai'),
            'ruangan' => $this->input->post('ruangan'),
        );
        
        // Cek bentrok jadwal (kecuali jadwal ini sendiri)
        if ($this->M_jadwal->check_conflict($data, $id)) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'Jadwal bentrok dengan jadwal yang sudah ada pada kelas, hari, dan jam yang sama'
            )));
            return;
        }
        
        $this->M_jadwal->update($id, $data);
        
        log_aktivitas('update', 'tb_jadwal', $id, 'Update jadwal pelajaran');
        
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'status' => TRUE,
            'message' => 'Jadwal berhasil diperbarui'
        )));
    }

    /**
     * AJAX: Hapus jadwal
     * 
     * @param string $encrypted_id ID terenkripsi
     * @return void
     */
    public function ajax_delete($encrypted_id) {
        $id = decrypt_id($encrypted_id);
        
        if (!$id) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'ID tidak valid'
            )));
            return;
        }
        
        $this->M_jadwal->delete($id);
        
        log_aktivitas('delete', 'tb_jadwal', $id, 'Hapus jadwal pelajaran');
        
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'status' => TRUE,
            'message' => 'Jadwal berhasil dihapus'
        )));
    }

    /**
     * AJAX: Get dropdown data
     * 
     * @return void
     */
    public function ajax_get_dropdown() {
        $type = $this->input->get('type');
        
        // Debug log
        error_log("Dropdown type requested: " . $type);
        error_log("GET params: " . print_r($_GET, true));
        
        $data = array();
        
        if (empty($type)) {
            $this->output->set_content_type('application/json')->set_output(json_encode(['results' => []]));
            return;
        }
        
        switch ($type) {
            case 'kelas':
                $result = $this->M_kelas->get_active_classes();
                error_log("Kelas result count: " . (is_array($result) ? count($result) : 'not array'));
                if ($result && is_array($result) && count($result) > 0) {
                    foreach ($result as $item) {
                        $data[] = array('id' => $item->id, 'text' => $item->nama_kelas);
                    }
                }
                break;
            case 'guru':
                $result = $this->M_guru->get_active_teachers();
                error_log("Guru result count: " . (is_array($result) ? count($result) : 'not array'));
                if ($result && is_array($result) && count($result) > 0) {
                    foreach ($result as $item) {
                        $data[] = array('id' => $item->id, 'text' => $item->nama_guru);
                    }
                }
                break;
            case 'mapel':
                $result = $this->M_matapelajaran->get_active_subjects();
                error_log("Mapel result count: " . (is_array($result) ? count($result) : 'not array'));
                if ($result && is_array($result) && count($result) > 0) {
                    foreach ($result as $item) {
                        $data[] = array('id' => $item->id, 'text' => $item->nama_mapel);
                    }
                }
                break;
            default:
                error_log("Unknown dropdown type: " . $type);
                break;
        }
        
        error_log("Dropdown data count for {$type}: " . count($data));
        error_log("Dropdown data: " . json_encode($data));
        
        $this->output->set_content_type('application/json')->set_output(json_encode(['results' => $data]));
    }

    /**
     * Validasi input form
     * 
     * @return array|bool TRUE jika valid, array error jika tidak
     */
    private function _validate() {
        $data = array();
        $data['error'] = array();
        $data['status'] = TRUE;
        
        $this->form_validation->set_rules('id_kelas', 'Kelas', 'required');
        $this->form_validation->set_rules('id_guru', 'Guru', 'required');
        $this->form_validation->set_rules('id_mapel', 'Mata Pelajaran', 'required');
        $this->form_validation->set_rules('hari', 'Hari', 'required');
        $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
        $this->form_validation->set_rules('jam_selesai', 'Jam Selesai', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $fields = array('id_kelas', 'id_guru', 'id_mapel', 'hari', 'jam_mulai', 'jam_selesai');
            foreach ($fields as $field) {
                if (form_error($field)) {
                    $data['error'][$field] = form_error($field);
                }
            }
            $data['status'] = FALSE;
            return $data;
        }
        
        // Check jam selesai > jam mulai
        $jam_mulai = $this->input->post('jam_mulai');
        $jam_selesai = $this->input->post('jam_selesai');
        if ($jam_selesai <= $jam_mulai) {
            $data['error']['jam_selesai'] = 'Jam selesai harus lebih besar dari jam mulai';
            $data['status'] = FALSE;
            return $data;
        }
        
        return TRUE;
    }

    /**
     * Generate PDF jadwal dengan filter
     * 
     * @return void
     */
    public function generate_pdf() {
        // Get filter parameters
        $filter_id_kelas = $this->input->get('id_kelas');
        $filter_hari = $this->input->get('hari');
        
        // Load data with filters
        $jadwal_list = $this->M_jadwal->get_all_jadwal($this->tahun_ajaran_aktif->id, $filter_id_kelas, $filter_hari);
        
        // Prepare data for PDF
        $data = array(
            'jadwal_list' => $jadwal_list,
            'tahun_ajaran' => $this->tahun_ajaran_aktif,
            'filter_info' => array(),
            'generated_date' => date('d F Y H:i:s')
        );
        
        // Build filter info
        if (!empty($filter_id_kelas)) {
            $kelas_data = $this->M_kelas->get_by_id($filter_id_kelas);
            if ($kelas_data) {
                $data['filter_info'][] = 'Kelas: ' . $kelas_data->nama_kelas;
            }
        }
        
        if (!empty($filter_hari)) {
            $data['filter_info'][] = 'Hari: ' . $filter_hari;
        }
        
        // Load PDF library (TCPDF)
        require_once(APPPATH . '../vendor/autoload.php');
        
        // Create PDF
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sistem Presensi Kelas');
        $pdf->SetTitle('Jadwal Pelajaran');
        $pdf->SetSubject('Jadwal Pelajaran');
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        
        // Add page
        $pdf->AddPage();
        
        // Build HTML content
        $html = '<h2 style="text-align: center; margin-bottom: 10px;">JADWAL PELAJARAN</h2>';
        $html .= '<h4 style="text-align: center; margin-bottom: 20px;">Tahun Ajaran ' . $this->tahun_ajaran_aktif->tahun_ajaran . ' (Semester ' . $this->tahun_ajaran_aktif->semester . ')</h4>';
        
        if (!empty($data['filter_info'])) {
            $html .= '<p style="margin-bottom: 15px;"><strong>Filter:</strong> ' . implode(', ', $data['filter_info']) . '</p>';
        }
        
        // Define day order
        $days_order = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        // Group jadwal by hari and kelas
        $grouped_jadwal = array();
        foreach ($jadwal_list as $jadwal) {
            $kelas = $jadwal->nama_kelas;
            $hari = $jadwal->hari;
            
            if (!isset($grouped_jadwal[$kelas])) {
                $grouped_jadwal[$kelas] = array();
            }
            if (!isset($grouped_jadwal[$kelas][$hari])) {
                $grouped_jadwal[$kelas][$hari] = array();
            }
            $grouped_jadwal[$kelas][$hari][] = $jadwal;
        }
        
        // Sort jadwal within each day by jam_mulai
        foreach ($grouped_jadwal as $kelas => &$hari_data) {
            foreach ($hari_data as $hari => &$jadwal_array) {
                usort($jadwal_array, function($a, $b) {
                    return strcmp($a->jam_mulai, $b->jam_mulai);
                });
            }
        }
        
        // Generate table per kelas and per hari
        foreach ($grouped_jadwal as $kelas => $hari_data) {
            // Kelas title
            $html .= '<div style="background-color: #4CAF50; color: white; padding: 8px; font-weight: bold; font-size: 14px; margin-top: 20px; margin-bottom: 10px;">';
            $html .= 'KELAS: ' . strtoupper($kelas);
            $html .= '</div>';
            
            // Generate table for each day
            foreach ($days_order as $day) {
                if (isset($hari_data[$day]) && !empty($hari_data[$day])) {
                    // Day header
                    $html .= '<div style="background-color: #2196F3; color: white; padding: 6px; font-weight: bold; font-size: 12px; margin-top: 15px; margin-bottom: 8px;">';
                    $html .= 'HARI ' . strtoupper($day);
                    $html .= '</div>';
                    
                    // Table for this day
                    $html .= '<table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 10px;">';
                    $html .= '<thead>';
                    $html .= '<tr style="background-color: #f0f0f0;">';
                    $html .= '<th style="width: 5%; text-align: center;">No</th>';
                    $html .= '<th style="width: 15%;">Jam</th>';
                    $html .= '<th style="width: 35%;">Mata Pelajaran</th>';
                    $html .= '<th style="width: 35%;">Guru</th>';
                    $html .= '<th style="width: 10%;">Ruangan</th>';
                    $html .= '</tr>';
                    $html .= '</thead>';
                    $html .= '<tbody>';
                    
                    // Table body
                    $no = 1;
                    foreach ($hari_data[$day] as $jadwal) {
                        $html .= '<tr>';
                        $html .= '<td style="text-align: center;">' . $no++ . '</td>';
                        $html .= '<td>' . $jadwal->jam_mulai . ' - ' . $jadwal->jam_selesai . '</td>';
                        $html .= '<td>' . $jadwal->nama_mapel . '</td>';
                        $html .= '<td>' . $jadwal->nama_guru . '</td>';
                        $html .= '<td>' . ($jadwal->ruangan ?? '-') . '</td>';
                        $html .= '</tr>';
                    }
                    
                    $html .= '</tbody>';
                    $html .= '</table>';
                }
            }
        }
        
        // If no data
        if (empty($jadwal_list)) {
            $html .= '<p style="text-align: center; font-style: italic; margin-top: 30px;">Tidak ada data jadwal yang sesuai dengan filter.</p>';
        }
        
        // Footer
        $html .= '<div style="margin-top: 20px; font-size: 9px; text-align: right;">';
        $html .= 'Dicetak pada: ' . $data['generated_date'];
        $html .= '</div>';
        
        // Output HTML
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Close and output PDF
        $filename = 'jadwal_pelajaran_' . date('YmdHis') . '.pdf';
        $pdf->Output($filename, 'I');
    }
}