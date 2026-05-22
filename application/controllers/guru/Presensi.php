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
     * Proses simpan presensi
     */
    public function simpan() {
        // Log untuk debugging
        log_message('debug', 'Presensi::simpan - Method: ' . $this->input->method());
        
        $csrf_name = $this->security->get_csrf_token_name();
        
        // Ambil data dari POST (format FormData)
        $id_jadwal = $this->input->post('id_jadwal');
        $tanggal = $this->input->post('tanggal');
        $materi_pelajaran = trim($this->input->post('materi_pelajaran'));
        
        log_message('debug', 'Presensi::simpan - id_jadwal: ' . $id_jadwal . ', tanggal: ' . $tanggal . ', materi: ' . substr($materi_pelajaran, 0, 50));
        
        // Ambil data siswa secara individual (format FormData: siswa[ID][status])
        $siswa_data = [];
        
        // Iterasi melalui semua input POST untuk mencari data siswa
        foreach ($_POST as $key => $value) {
            // Match pattern: siswa[123][status] atau siswa[123][keterangan]
            if (preg_match('/^siswa\[(\d+)\]\[(status|keterangan)\]$/', $key, $matches)) {
                $id_siswa = $matches[1];
                $field = $matches[2];
                
                if (!isset($siswa_data[$id_siswa])) {
                    $siswa_data[$id_siswa] = [];
                }
                $siswa_data[$id_siswa][$field] = $value;
            }
        }
        
        log_message('debug', 'Presensi::simpan - siswa_data count: ' . count($siswa_data));
        log_message('debug', 'Presensi::simpan - siswa_data sample: ' . json_encode(array_slice($siswa_data, 0, 2)));
        
        // Cek apakah ini request AJAX
        $is_ajax = $this->input->is_ajax_request();
        
        if ($this->input->method() !== 'post') {
            log_message('error', 'Presensi::simpan - Method tidak diizinkan');
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'pesan' => 'Method tidak diizinkan'
                ]);
                return;
            }
            show_error('Method tidak diizinkan', 403);
        }

        // Validasi data dasar
        if (!$id_jadwal || !$tanggal || empty($materi_pelajaran)) {
            log_message('error', 'Presensi::simpan - Validasi gagal: id_jadwal=' . $id_jadwal . ', tanggal=' . $tanggal . ', materi kosong=' . (empty($materi_pelajaran) ? 'true' : 'false'));
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'pesan' => 'Materi pelajaran wajib diisi dan data harus lengkap'
                ]);
                return;
            }
            $this->session->set_flashdata('error', 'Materi pelajaran wajib diisi');
            redirect('guru/presensi/form/' . encrypt_id($id_jadwal));
        }

        if (empty($siswa_data)) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'pesan' => 'Tidak ada data siswa yang diproses'
                ]);
                return;
            }
            $this->session->set_flashdata('error', 'Tidak ada data siswa');
            redirect('guru/presensi/form/' . encrypt_id($id_jadwal));
        }
        
        // Validasi jadwal
        $jadwal = $this->M_presensi->get_jadwal_detail($id_jadwal);
        if (!$jadwal) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'pesan' => 'Jadwal tidak ditemukan'
                ]);
                return;
            }
            $this->session->set_flashdata('error', 'Jadwal tidak ditemukan');
            redirect('guru/presensi');
        }
        
        // Validasi guru
        $id_user = $this->session->userdata('id');
        if ($jadwal['id_guru_user'] != $id_user) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'pesan' => 'Anda tidak memiliki akses ke jadwal ini'
                ]);
                return;
            }
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke jadwal ini');
            redirect('guru/presensi');
        }
        
        // Cek apakah sudah ada presensi
        $presensi_exists = $this->M_presensi->check_presensi_exists($id_jadwal, $tanggal);
        if ($presensi_exists) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'pesan' => 'Presensi untuk jadwal ini sudah diinput pada tanggal ' . tanggal_indo($tanggal)
                ]);
                return;
            }
            $this->session->set_flashdata('error', 'Presensi untuk jadwal ini sudah diinput pada tanggal ' . tanggal_indo($tanggal));
            redirect('guru/presensi');
        }
        
        // Get data id_guru dari tb_jadwal
        $this->db->where('id', $id_jadwal);
        $jadwal_row = $this->db->get('tb_jadwal')->row();
        $id_guru = $jadwal_row->id_guru ?? null;
        
        log_message('debug', 'Presensi::simpan - Getting guru ID from jadwal. id_guru: ' . $id_guru);
        
        // Simpan presensi
        $result = $this->M_presensi->simpan_presensi([
            'id_jadwal' => $id_jadwal,
            'id_guru' => $id_guru,
            'tanggal' => $tanggal,
            'materi_pelajaran' => $materi_pelajaran,
            'siswa_data' => $siswa_data
        ]);
        
        log_message('debug', 'Presensi::simpan - Result from model: ' . json_encode($result));
        
        if ($is_ajax) {
            header('Content-Type: application/json');
            if ($result['success']) {
                log_aktivitas('input_presensi', 'tb_presensi', $result['id_presensi'], 'Input presensi untuk mata pelajaran ' . $jadwal['nama_mapel'] . ' kelas ' . $jadwal['nama_kelas']);
                echo json_encode([
                    'status' => 'success',
                    'pesan' => 'Data presensi berhasil disimpan untuk ' . count($siswa_data) . ' siswa.',
                    'csrf_hash' => $this->security->get_csrf_hash()
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'pesan' => $result['message']
                ]);
            }
            return;
        }
        
        // Fallback untuk non-AJAX (form submit biasa)
        if ($result['success']) {
            log_aktivitas('input_presensi', 'tb_presensi', $result['id_presensi'], 'Input presensi untuk mata pelajaran ' . $jadwal['nama_mapel'] . ' kelas ' . $jadwal['nama_kelas']);
            $this->session->set_flashdata('success', 'Data presensi berhasil disimpan untuk ' . count($siswa_data) . ' siswa.');
            redirect('guru/presensi');
        } else {
            $this->session->set_flashdata('error', $result['message']);
            redirect('guru/presensi/form/' . encrypt_id($id_jadwal));
        }
    }
    
    /**
     * Edit presensi yang sudah diinput
     * @param string $encrypted_id ID presensi terenkripsi
     */
    public function edit($encrypted_id) {
        $id_presensi = decrypt_id($encrypted_id);
        
        if (!$id_presensi) {
            $this->session->set_flashdata('error', 'ID presensi tidak valid');
            redirect('guru/rekap');
        }
        
        // Get data presensi
        $presensi = $this->M_presensi->get_presensi_detail($id_presensi);
        
        if (!$presensi) {
            $this->session->set_flashdata('error', 'Data presensi tidak ditemukan');
            redirect('guru/rekap');
        }
        
        // Validasi bahwa presensi milik guru yang login
        $id_user = $this->session->userdata('id');
        if ($presensi['id_guru_user'] != $id_user) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke data presensi ini');
            redirect('guru/rekap');
        }
        
        $this->data['page_title'] = 'Edit Presensi';
        $this->data['presensi'] = $presensi;
        $this->data['siswa_list'] = $this->M_presensi->get_siswa_presensi($id_presensi);
        
        $this->render_template('guru/presensi_edit', $this->data);
    }
    
    /**
     * Proses update presensi
     */
    public function update() {
        if ($this->input->method() !== 'post') {
            show_error('Method tidak diizinkan', 403);
        }
        
        $id_presensi = $this->input->post('id_presensi');
        $materi_pelajaran = $this->input->post('materi_pelajaran');
        $siswa_data = $this->input->post('siswa');
        
        if (!$id_presensi || empty($siswa_data)) {
            $this->session->set_flashdata('error', 'Data presensi tidak lengkap');
            redirect('guru/presensi/edit/' . encrypt_id($id_presensi));
        }
        
        // Get data presensi
        $presensi = $this->M_presensi->get_presensi_detail($id_presensi);
        if (!$presensi) {
            $this->session->set_flashdata('error', 'Data presensi tidak ditemukan');
            redirect('guru/rekap');
        }
        
        // Validasi guru
        $id_user = $this->session->userdata('id');
        if ($presensi['id_guru_user'] != $id_user) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke data presensi ini');
            redirect('guru/rekap');
        }
        
        // Update presensi
        $result = $this->M_presensi->update_presensi([
            'id_presensi' => $id_presensi,
            'materi_pelajaran' => $materi_pelajaran,
            'siswa_data' => $siswa_data
        ]);
        
        if ($result['success']) {
            log_aktivitas('update_presensi', 'tb_presensi', $id_presensi, 'Update presensi untuk mata pelajaran ' . $presensi['nama_mapel'] . ' kelas ' . $presensi['nama_kelas']);
            $this->session->set_flashdata('success', 'Data presensi berhasil diperbarui.');
            redirect('guru/rekap');
        } else {
            $this->session->set_flashdata('error', $result['message']);
            redirect('guru/presensi/edit/' . encrypt_id($id_presensi));
        }
    }
}
