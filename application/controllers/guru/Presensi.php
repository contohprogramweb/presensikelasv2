<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller: Guru - Presensi
 * Mengelola input presensi siswa oleh guru
 */
class Presensi extends MY_Controller {
    
    protected $role_required = ['guru'];
    
    public function __construct() {
        parent::__construct();
        $this->load->model('guru/M_presensi');
        $this->load->helper('form');
    }
    
    /**
     * Halaman daftar jadwal untuk input presensi (hari ini)
     */
    public function index() {
        $this->data['page_title'] = 'Input Presensi';
        
        $id_user = $this->session->userdata('id');
        $id_tahun_ajaran = isset($this->tahun_ajaran_aktif->id) ? $this->tahun_ajaran_aktif->id : null;
        
        // Get jadwal hari ini
        $this->data['jadwal_hari_ini'] = $this->M_presensi->get_jadwal_hari_ini($id_user, $id_tahun_ajaran);
        $this->data['tahun_ajaran_aktif'] = $this->tahun_ajaran_aktif;
        
        // Cek status presensi untuk setiap jadwal hari ini
        $tanggal_hari_ini = date('Y-m-d');
        $status_presensi = [];
        foreach ($this->data['jadwal_hari_ini'] as $jadwal) {
            $presensi = $this->M_presensi->get_presensi_by_jadwal_tanggal($jadwal->id, $tanggal_hari_ini);
            $status_presensi[$jadwal->id] = $presensi ?: null;
        }
        $this->data['status_presensi'] = $status_presensi;
        $this->data['tanggal_hari_ini'] = $tanggal_hari_ini;
        
        // Hari ini dalam bahasa Indonesia
        $hari_indo = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $this->data['hari_ini_indo'] = $hari_indo[date('l')];
        
        $this->render_template('guru/presensi_form', $this->data);
    }
    
    /**
     * Form input presensi baru untuk jadwal tertentu
     */
    public function form($encrypted_id) {
        $id_jadwal = decrypt_id($encrypted_id);
        
        if (!$id_jadwal) {
            $this->session->set_flashdata('error', 'ID jadwal tidak valid');
            redirect('guru/presensi');
        }
        
        $jadwal = $this->M_presensi->get_jadwal_detail($id_jadwal);
        if (!$jadwal) {
            $this->session->set_flashdata('error', 'Jadwal tidak ditemukan');
            redirect('guru/presensi');
        }
        
        $id_user = $this->session->userdata('id');
        if ($jadwal['id_guru_user'] != $id_user) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke jadwal ini');
            redirect('guru/presensi');
        }
        
        $tanggal_hari_ini = date('Y-m-d');
        $presensi_exists = $this->M_presensi->check_presensi_exists($id_jadwal, $tanggal_hari_ini);
        
        if ($presensi_exists) {
            // Kalau sudah ada, arahkan ke edit
            $presensi = $this->M_presensi->get_presensi_by_jadwal_tanggal($id_jadwal, $tanggal_hari_ini);
            $this->session->set_flashdata('info', 'Presensi hari ini sudah diinput. Silakan edit jika diperlukan.');
            redirect('guru/presensi/edit/' . encrypt_id($presensi['id']));
        }
        
        $this->data['page_title'] = 'Input Presensi - ' . $jadwal['nama_mapel'];
        $this->data['jadwal'] = $jadwal;
        $this->data['tanggal'] = $tanggal_hari_ini;
        $this->data['siswa_list'] = $this->M_presensi->get_siswa_by_kelas($jadwal['id_kelas']);
        $this->data['mode'] = 'tambah';
        $this->data['presensi_existing'] = null;
        $this->data['siswa_presensi_map'] = [];
        
        $this->render_template('guru/presensi_input', $this->data);
    }
    
    /**
     * Form edit presensi yang sudah ada
     * @param string $encrypted_id ID presensi terenkripsi
     */
    public function edit($encrypted_id) {
        $id_presensi = decrypt_id($encrypted_id);
        
        if (!$id_presensi) {
            $this->session->set_flashdata('error', 'ID presensi tidak valid');
            redirect('guru/presensi');
        }
        
        $presensi = $this->M_presensi->get_presensi_detail($id_presensi);
        if (!$presensi) {
            $this->session->set_flashdata('error', 'Data presensi tidak ditemukan');
            redirect('guru/presensi');
        }
        
        // Validasi akses guru
        $id_user = $this->session->userdata('id');
        if ($presensi['id_guru_user'] != $id_user) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke presensi ini');
            redirect('guru/presensi');
        }
        
        $jadwal = $this->M_presensi->get_jadwal_detail($presensi['id_jadwal']);
        
        // Get data siswa presensi yang sudah ada → map id_siswa => {status, keterangan}
        $siswa_presensi_raw = $this->M_presensi->get_siswa_presensi($id_presensi);
        $siswa_presensi_map = [];
        foreach ($siswa_presensi_raw as $sp) {
            $siswa_presensi_map[$sp['id_siswa']] = [
                'status'     => $sp['status'],
                'keterangan' => $sp['keterangan'],
            ];
        }
        
        $this->data['page_title'] = 'Edit Presensi - ' . ($jadwal['nama_mapel'] ?? '');
        $this->data['jadwal'] = $jadwal;
        $this->data['tanggal'] = $presensi['tanggal'];
        $this->data['siswa_list'] = $this->M_presensi->get_siswa_by_kelas($jadwal['id_kelas']);
        $this->data['mode'] = 'edit';
        $this->data['presensi_existing'] = $presensi;
        $this->data['siswa_presensi_map'] = $siswa_presensi_map;
        
        $this->render_template('guru/presensi_input', $this->data);
    }
    
    /**
     * Simpan presensi baru (INSERT)
     */
    public function simpan() {
        log_message('debug', 'Presensi::simpan - POST data: ' . print_r($_POST, true));
        
        $id_jadwal        = $this->input->post('id_jadwal');
        $tanggal          = $this->input->post('tanggal');
        $materi_pelajaran = trim($this->input->post('materi_pelajaran'));
        $siswa_data       = $this->input->post('siswa');
        
        if (empty($id_jadwal) || empty($tanggal) || empty($materi_pelajaran)) {
            $this->session->set_flashdata('error', 'Data tidak lengkap! Materi pelajaran wajib diisi.');
            redirect('guru/presensi/form/' . encrypt_id($id_jadwal));
            return;
        }
        
        if (empty($siswa_data) || !is_array($siswa_data)) {
            $this->session->set_flashdata('error', 'Tidak ada data siswa!');
            redirect('guru/presensi/form/' . encrypt_id($id_jadwal));
            return;
        }
        
        $id_user = $this->session->userdata('id');
        $jadwal  = $this->M_presensi->get_jadwal_detail($id_jadwal);
        
        if (!$jadwal || $jadwal['id_guru_user'] != $id_user) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke jadwal ini!');
            redirect('guru/presensi');
            return;
        }
        
        $this->db->where('id_user', $id_user);
        $guru = $this->db->get('tb_guru')->row();
        
        if (!$guru) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan!');
            redirect('guru/presensi/form/' . encrypt_id($id_jadwal));
            return;
        }
        
        $data_presensi = [
            'tanggal'          => $tanggal,
            'materi_pelajaran' => $materi_pelajaran,
            'id_guru'          => $guru->id,
            'siswa'            => $siswa_data,
        ];
        
        $result = $this->M_presensi->simpan_presensi($data_presensi, $id_jadwal);
        
        if ($result['success']) {
            $this->session->set_flashdata('success', $result['message']);
            redirect('guru/presensi');
        } else {
            $this->session->set_flashdata('error', $result['message']);
            redirect('guru/presensi/form/' . encrypt_id($id_jadwal));
        }
    }
    
    /**
     * Update presensi yang sudah ada
     */
    public function update() {
        log_message('debug', 'Presensi::update - POST data: ' . print_r($_POST, true));
        
        $id_presensi      = $this->input->post('id_presensi');
        $materi_pelajaran = trim($this->input->post('materi_pelajaran'));
        $siswa_data       = $this->input->post('siswa');
        
        if (empty($id_presensi) || empty($materi_pelajaran)) {
            $this->session->set_flashdata('error', 'Data tidak lengkap! Materi pelajaran wajib diisi.');
            redirect('guru/presensi/edit/' . encrypt_id($id_presensi));
            return;
        }
        
        if (empty($siswa_data) || !is_array($siswa_data)) {
            $this->session->set_flashdata('error', 'Tidak ada data siswa!');
            redirect('guru/presensi/edit/' . encrypt_id($id_presensi));
            return;
        }
        
        $id_user = $this->session->userdata('id');
        $presensi = $this->M_presensi->get_presensi_detail($id_presensi);
        
        if (!$presensi || $presensi['id_guru_user'] != $id_user) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke presensi ini!');
            redirect('guru/presensi');
            return;
        }
        
        $data_update = [
            'materi_pelajaran' => $materi_pelajaran,
            'siswa'            => $siswa_data,
            'tanggal'          => $presensi['tanggal'],
        ];
        
        $result = $this->M_presensi->update_presensi($id_presensi, $data_update);
        
        if ($result['success']) {
            $this->session->set_flashdata('success', $result['message']);
            redirect('guru/presensi');
        } else {
            $this->session->set_flashdata('error', $result['message']);
            redirect('guru/presensi/edit/' . encrypt_id($id_presensi));
        }
    }
}