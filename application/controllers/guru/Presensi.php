<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presensi extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->role_required = ['guru'];
        $this->load->model('guru/M_presensi');
        $this->load->model('guru/M_jadwal');
    }

    public function index()
    {
        $data['judul'] = 'Input Presensi';

        $user_id = $this->session->userdata('id');
        $this->db->where('id_user', $user_id);
        $guru = $this->db->get('tb_guru')->row_array();

        if (!$guru) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan. Hubungi administrator.');
            redirect('profil');
            return;
        }

        $id_tahun_ajaran = ($this->tahun_ajaran_aktif && isset($this->tahun_ajaran_aktif->id))
            ? (int) $this->tahun_ajaran_aktif->id
            : null;

        $data['jadwal_hari_ini']    = $this->M_jadwal->get_jadwal_hari_ini($user_id, $id_tahun_ajaran);
        $data['tahun_ajaran_aktif'] = $this->tahun_ajaran_aktif;

        $hari_indo = [
            'Sunday' => 'Minggu', 'Monday'  => 'Senin',  'Tuesday'  => 'Selasa',
            'Wednesday' => 'Rabu','Thursday' => 'Kamis',  'Friday'   => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $data['hari_ini_indo'] = $hari_indo[date('l')];

        $this->render_template('guru/presensi_form', $data);
    }

    public function form($id_jadwal, $tanggal = null)
    {
        $id_decrypted = decrypt_id($id_jadwal);
        if (!$id_decrypted) { show_403(); return; }

        $jadwal = $this->M_jadwal->get_by_id($id_decrypted);
        if (!$jadwal) { show_403(); return; }

        $data['judul']             = 'Form Presensi';
        $data['jadwal']            = $jadwal;
        $data['tanggal']           = $tanggal ?: date('Y-m-d');
        $data['siswa']             = $this->M_presensi->get_siswa_by_kelas($jadwal['id_kelas']);
        $data['existing_presensi'] = $this->M_presensi->get_presensi_by_jadwal_tanggal(
            $id_decrypted, $data['tanggal']
        );

        $this->render_template('guru/presensi_input', $data);
    }

    public function simpan()
    {
        // Bersihkan semua output sebelumnya (PHP notice/warning dari environment development)
        if (ob_get_level()) {
            ob_end_clean();
        }
        ob_start();

        // Nonaktifkan display error agar tidak mencemari JSON
        @ini_set('display_errors', 0);

        header('Content-Type: application/json; charset=utf-8');

        // Helper untuk kirim JSON dan exit
        $json = function($data) {
            // Bersihkan buffer lagi sebelum output final
            if (ob_get_level()) ob_end_clean();
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        };

        // Hanya terima POST
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            $json(['status' => false, 'message' => 'Method tidak diizinkan.']);
        }

        // Ambil input
        $id_jadwal_enc  = $this->input->post('id_jadwal');
        $tanggal        = $this->input->post('tanggal');
        $materi         = trim((string)$this->input->post('materi_pelajaran'));
        $siswa_ids      = $this->input->post('id_siswa');
        $status_arr     = $this->input->post('status');
        $keterangan_arr = $this->input->post('keterangan');

        // Validasi dasar
        if (empty($materi) || mb_strlen($materi) < 5) {
            $json(['status' => false, 'message' => 'Materi pelajaran minimal 5 karakter.']);
        }

        $id_jadwal = decrypt_id($id_jadwal_enc);
        if (!$id_jadwal) {
            $json(['status' => false, 'message' => 'Data jadwal tidak valid.']);
        }

        if (empty($tanggal)) {
            $json(['status' => false, 'message' => 'Tanggal tidak valid.']);
        }

        if (empty($siswa_ids) || !is_array($siswa_ids)) {
            $json(['status' => false, 'message' => 'Tidak ada data siswa yang dikirim.']);
        }

        // Ambil data guru
        $user_id = $this->session->userdata('id');
        $this->db->where('id_user', $user_id);
        $guru = $this->db->get('tb_guru')->row_array();

        if (!$guru) {
            $json(['status' => false, 'message' => 'Data guru tidak ditemukan.']);
        }

        // Susun data presensi
        $data_presensi = [];

        foreach ($siswa_ids as $key => $id_siswa) {
            $status     = isset($status_arr[$key])     ? $status_arr[$key]     : '';
            $keterangan = isset($keterangan_arr[$key]) ? trim((string)$keterangan_arr[$key]) : '';

            if (!in_array($status, ['Hadir', 'Izin', 'Sakit', 'Alpa'])) {
                $json(['status' => false, 'message' => 'Status kehadiran tidak valid.']);
            }

            if (in_array($status, ['Izin', 'Sakit']) && mb_strlen($keterangan) < 10) {
                $json(['status' => false, 'message' => 'Keterangan minimal 10 karakter untuk Izin/Sakit.']);
            }

            $data_presensi[] = [
                'id_jadwal'        => (int) $id_jadwal,
                'id_siswa'         => (int) $id_siswa,
                'tanggal'          => $tanggal,
                'status'           => $status,
                'materi_pelajaran' => $materi,
                'keterangan'       => in_array($status, ['Hadir', 'Alpa']) ? null : $keterangan,
                'id_guru'          => (int) $guru['id'],
            ];
        }

        // Simpan
        $result = $this->M_presensi->simpan_presensi($data_presensi);

        if ($result['status']) {
            log_aktivitas('INSERT_PRESENSI', 'tb_presensi', $id_jadwal, 'Presensi tanggal ' . $tanggal);
            $json(['status' => true, 'message' => 'Presensi berhasil disimpan.']);
        } else {
            log_message('error', 'Presensi::simpan gagal - jadwal=' . $id_jadwal . ' tgl=' . $tanggal);
            $json(['status' => false, 'message' => 'Gagal menyimpan ke database. Periksa log aplikasi.']);
        }
    }
}
