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
        
        // Hari ini dalam bahasa Indonesia
        $hari_indo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $this->data['hari_ini_indo'] = $hari_indo[date('l')];
        
        $this->render_template('guru/presensi_form', $this->data);
    }
    
    /**
     * Form input presensi untuk jadwal tertentu
     * @param string $encrypted_id ID jadwal terenkripsi
     */
    public function form($encrypted_id) {
        $id_jadwal = decrypt_id($encrypted_id);
        
        if (!$id_jadwal) {
            $this->session->set_flashdata('error', 'ID jadwal tidak valid');
            redirect('guru/presensi');
        }
        
        // Get data jadwal
        $jadwal = $this->M_presensi->get_jadwal_detail($id_jadwal);
        
        if (!$jadwal) {
            $this->session->set_flashdata('error', 'Jadwal tidak ditemukan');
            redirect('guru/presensi');
        }
        
        // Validasi bahwa jadwal milik guru yang login
        $id_user = $this->session->userdata('id');
        if ($jadwal['id_guru_user'] != $id_user) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke jadwal ini');
            redirect('guru/presensi');
        }
        
        // Cek apakah sudah ada presensi untuk jadwal ini hari ini
        $tanggal_hari_ini = date('Y-m-d');
        $presensi_exists = $this->M_presensi->check_presensi_exists($id_jadwal, $tanggal_hari_ini);
        
        if ($presensi_exists) {
            $this->session->set_flashdata('info', 'Presensi untuk jadwal ini sudah diinput hari ini.');
            redirect('guru/rekap');
        }
        
        $this->data['page_title'] = 'Input Presensi - ' . $jadwal['nama_mapel'];
        $this->data['jadwal'] = $jadwal;
        $this->data['tanggal'] = $tanggal_hari_ini;
        
        // Get daftar siswa di kelas tersebut
        $this->data['siswa_list'] = $this->M_presensi->get_siswa_by_kelas($jadwal['id_kelas']);
        
        $this->render_template('guru/presensi_input', $this->data);
    }
    
    /**
     * Simpan presensi via POST form biasa
     * Handles both create and update
     */
    public function simpan() {
        $id_jadwal = $this->input->post('id_jadwal');
        $tanggal = $this->input->post('tanggal');
        $materi_pelajaran = trim($this->input->post('materi_pelajaran'));
        $siswa_data = $this->input->post('siswa');
        
        // Validasi input
        if (empty($id_jadwal) || empty($tanggal) || empty($materi_pelajaran)) {
            $this->session->set_flashdata('error', 'Data tidak lengkap! Materi pelajaran wajib diisi.');
            redirect("guru/presensi/form/{$id_jadwal}");
            return;
        }
        
        if (empty($siswa_data) || !is_array($siswa_data)) {
            $this->session->set_flashdata('error', 'Tidak ada data siswa!');
            redirect("guru/presensi/form/{$id_jadwal}");
            return;
        }
        
        // Validasi jadwal milik guru yang login
        $id_user = $this->session->userdata('id');
        $jadwal = $this->M_presensi->get_jadwal_detail($id_jadwal);
        
        if (!$jadwal || $jadwal['id_guru_user'] != $id_user) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke jadwal ini!');
            redirect("guru/presensi/form/{$id_jadwal}");
            return;
        }
        
        // Dapatkan id_guru dari tb_guru
        $this->db->where('id_user', $id_user);
        $guru = $this->db->get('tb_guru')->row();
        
        if (!$guru) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan!');
            redirect("guru/presensi/form/{$id_jadwal}");
            return;
        }
        
        $id_guru = $guru->id;
        
        // Siapkan data untuk model
        $data_presensi = [
            'tanggal' => $tanggal,
            'materi_pelajaran' => $materi_pelajaran,
            'id_guru' => $id_guru,
            'siswa' => $siswa_data
        ];
        
        // Gunakan method simpan_presensi di model
        $result = $this->M_presensi->simpan_presensi($data_presensi, $id_jadwal);
        
        if ($result['success']) {
            $this->session->set_flashdata('success', $result['message']);
            redirect('guru/rekap');
        } else {
            log_message('error', 'Gagal menyimpan presensi: ' . $result['message']);
            $this->session->set_flashdata('error', $result['message']);
            redirect("guru/presensi/form/{$id_jadwal}");
        }
    }
    
}