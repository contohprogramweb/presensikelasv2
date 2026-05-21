<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Kelassiswa - Admin
 * Mengelola penempatan siswa ke kelas
 * 
 * @package     CodeIgniter
 * @subpackage  Controllers
 * @category    Admin
 */
class Kelassiswa extends MY_Controller {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        
        // Set role
        $this->role_required = array('admin');
        
        // Load model
        $this->load->model('admin/M_kelassiswa');
        $this->load->model('admin/M_kelas');
        $this->load->model('admin/M_siswa');
        
        // Set data global
        $this->data['page_title'] = 'Penempatan Siswa ke Kelas';
        $this->data['active_menu'] = 'kelassiswa';
    }

    /**
     * Halaman utama penempatan siswa
     * 
     * @return void
     */
    public function index() {
        $this->data['content'] = 'admin/kelassiswa';
        $this->load->view('templates/template', $this->data);
    }

    /**
     * AJAX: Get list kelas
     * 
     * @return void
     */
    public function ajax_get_kelas() {
        $kelas_list = $this->M_kelas->get_all($this->tahun_ajaran_aktif);
        
        $data = array();
        foreach ($kelas_list as $item) {
            $row = array();
            $row[] = $item['nama_kelas'];
            $row[] = $item['wali_nama'] ?? '-';
            
            // Count siswa
            $jumlah_siswa = $this->M_kelassiswa->count_siswa_by_kelas($item['id']);
            $row[] = $jumlah_siswa;
            
            // Action button
            $action = '<button type="button" class="btn btn-sm btn-primary" onclick="kelola_siswa(\'' . encrypt_id($item['id']) . '\', \'' . $item['nama_kelas'] . '\')"><i class="fas fa-users"></i> Kelola Siswa</button>';
            $row[] = $action;
            
            $data[] = $row;
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode(array('data' => $data)));
    }

    /**
     * AJAX: Get siswa untuk modal
     * 
     * @param string $encrypted_id_kelas ID kelas terenkripsi
     * @return void
     */
    public function ajax_get_siswa($encrypted_id_kelas) {
        $id_kelas = decrypt_id($encrypted_id_kelas);
        
        if (!$id_kelas) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'ID kelas tidak valid'
            )));
            return;
        }
        
        // Get siswa yang sudah di kelas ini
        $sudah_ditempatkan = $this->M_kelassiswa->get_siswa_by_kelas($id_kelas);
        $placed_ids = array();
        foreach ($sudah_ditempatkan as $s) {
            $placed_ids[] = $s->id;
        }
        
        // Get semua siswa
        $semua_siswa = $this->M_siswa->get_all_students();
        
        $tersedia = array();
        $ditempatkan = array();
        
        foreach ($semua_siswa as $siswa) {
            $row = array(
                'id' => $siswa->id,
                'nis' => $siswa->nis,
                'nama' => $siswa->nama_siswa,
                'jk' => $siswa->jenis_kelamin
            );
            
            if (in_array($siswa->id, $placed_ids)) {
                $ditempatkan[] = $row;
            } else {
                $tersedia[] = $row;
            }
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'status' => TRUE,
            'tersedia' => $tersedia,
            'ditempatkan' => $ditempatkan
        )));
    }

    /**
     * AJAX: Simpan penempatan siswa
     * 
     * @return void
     */
    public function simpan_penempatan() {
        $id_kelas = $this->input->post('id_kelas');
        $id_siswa_arr = $this->input->post('id_siswa');
        
        if (!$id_kelas || !is_array($id_siswa_arr) || empty($id_siswa_arr)) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'Data tidak valid'
            )));
            return;
        }
        
        $id_kelas = decrypt_id($id_kelas);
        
        if (!$id_kelas) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'ID kelas tidak valid'
            )));
            return;
        }
        
        // Mulai transaction
        $this->db->trans_start();
        
        $success_count = 0;
        $failed_count = 0;
        
        foreach ($id_siswa_arr as $id_siswa) {
            $id_siswa = decrypt_id($id_siswa);
            
            if (!$id_siswa) {
                $failed_count++;
                continue;
            }
            
            // Update tb_siswa.id_kelas
            $this->db->where('id', $id_siswa)
                     ->update('tb_siswa', array('id_kelas' => $id_kelas));
            
            // Insert ke tb_riwayat_kelas
            $riwayat_data = array(
                'id_siswa' => $id_siswa,
                'id_kelas' => $id_kelas,
                'id_tahun_ajaran' => $this->tahun_ajaran_aktif,
                'status' => 'naik',
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('tb_riwayat_kelas', $riwayat_data);
            
            $success_count++;
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            $this->output->set_status_header(500);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'Gagal menyimpan penempatan siswa'
            )));
            return;
        }
        
        log_aktivitas('update', 'tb_siswa', null, "Penempatan {$success_count} siswa ke kelas");
        
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'status' => TRUE,
            'message' => "Berhasil menempatkan {$success_count} siswa. Gagal: {$failed_count}"
        )));
    }

    /**
     * AJAX: Hapus siswa dari kelas
     * 
     * @return void
     */
    public function ajax_hapus_siswa() {
        $id_siswa = decrypt_id($this->input->post('id_siswa'));
        
        if (!$id_siswa) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(array(
                'status' => FALSE,
                'message' => 'ID siswa tidak valid'
            )));
            return;
        }
        
        // Update tb_siswa.id_kelas = NULL
        $this->db->where('id', $id_siswa)
                 ->update('tb_siswa', array('id_kelas' => null));
        
        log_aktivitas('update', 'tb_siswa', $id_siswa, 'Hapus siswa dari kelas');
        
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'status' => TRUE,
            'message' => 'Siswa berhasil dikeluarkan dari kelas'
        )));
    }

    /**
     * AJAX: Get statistik penempatan
     * 
     * @return void
     */
    public function ajax_get_statistik() {
        $total_siswa = $this->db->count_all('tb_siswa');
        $sudah_ditempatkan = $this->db->where('id_kelas IS NOT NULL')->count_all_results('tb_siswa');
        $belum_ditempatkan = $total_siswa - $sudah_ditempatkan;
        
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'total_siswa' => $total_siswa,
            'sudah_ditempatkan' => $sudah_ditempatkan,
            'belum_ditempatkan' => $belum_ditempatkan
        )));
    }
}
