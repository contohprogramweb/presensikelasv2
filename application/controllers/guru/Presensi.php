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
            $this->session->set_flashdata('error', 'Data tidak lengkap!');
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
        
        // Cek apakah sudah ada presensi untuk jadwal dan tanggal ini
        $existing_presensi = $this->M_presensi->get_presensi_by_jadwal_tanggal($id_jadwal, $tanggal);
        
        $this->db->trans_start();
        
        $id_presensi = null;
        
        if ($existing_presensi) {
            // UPDATE existing presensi
            $id_presensi = $existing_presensi['id'];
            
            // Update materi pelajaran
            $this->db->where('id', $id_presensi);
            $this->db->update('tb_presensi', [
                'materi_pelajaran' => $materi_pelajaran,
                'id_guru' => $id_guru
            ]);
            
            // Delete existing siswa presensi
            $this->db->where('id_presensi', $id_presensi);
            $this->db->delete('tb_presensi_siswa');
            
        } else {
            // CREATE new presensi
            $presensi_data = [
                'id_jadwal' => $id_jadwal,
                'id_guru' => $id_guru,
                'materi_pelajaran' => $materi_pelajaran,
                'tanggal' => $tanggal,
                'waktu_input' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->insert('tb_presensi', $presensi_data);
            $id_presensi = $this->db->insert_id();
        }
        
        // Insert data siswa presensi
        if ($id_presensi && is_array($siswa_data)) {
            foreach ($siswa_data as $id_siswa => $data_siswa) {
                $status = isset($data_siswa['status']) ? $data_siswa['status'] : 'Hadir';
                $keterangan = isset($data_siswa['keterangan']) ? htmlspecialchars(trim($data_siswa['keterangan'])) : null;
                
                $presensi_siswa_data = [
                    'id_presensi' => $id_presensi,
                    'id_siswa' => $id_siswa,
                    'tanggal' => $tanggal,
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $this->db->insert('tb_presensi_siswa', $presensi_siswa_data);
            }
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Transaksi database gagal saat menyimpan presensi');
            $this->session->set_flashdata('error', 'Gagal menyimpan presensi!');
            redirect("guru/presensi/form/{$id_jadwal}");
        } else {
            $action = $existing_presensi ? 'diperbarui' : 'disimpan';
            $this->session->set_flashdata('success', "Presensi berhasil {$action}!");
            redirect('guru/rekap');
        }
    }
    
}