<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presensi extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->role_required = ['guru'];
        $this->load->model('guru/M_presensi');
        $this->load->model('guru/M_jadwal');
        $this->load->model('M_dashboard');
        $this->load->library('form_validation');
        
        // Load tahun ajaran aktif
        $this->tahun_ajaran_aktif = $this->M_dashboard->get_tahun_ajaran_aktif();
        
        // Fallback ke tahun ajaran terbaru jika tidak ada yang aktif
        if (!$this->tahun_ajaran_aktif) {
            $this->tahun_ajaran_aktif = $this->M_dashboard->get_tahun_ajaran_terbaru();
        }
    }

    public function index()
    {
        $data['judul'] = 'Input Presensi';
        
        // Get guru ID from session
        $user_id = $this->session->userdata('id');
        $this->db->where('id_user', $user_id);
        $guru = $this->db->get('tb_guru')->row_array();
        
        if (!$guru) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan. Hubungi administrator.');
            redirect('profil');
            return;
        }
        
        // Pastikan tahun ajaran aktif ada
        $id_tahun_ajaran = null;
        if ($this->tahun_ajaran_aktif && isset($this->tahun_ajaran_aktif->id)) {
            $id_tahun_ajaran = (int)$this->tahun_ajaran_aktif->id;
        }
        
        // Ambil jadwal hari ini - passing user_id, bukan guru_id
        $data['jadwal_hari_ini'] = $this->M_jadwal->get_jadwal_hari_ini($user_id, $id_tahun_ajaran);
        
        // Data untuk view
        $hari_indo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $data['hari_ini_indo'] = $hari_indo[date('l')];
        $data['tahun_ajaran_aktif'] = $this->tahun_ajaran_aktif;
        
        $this->render_template('guru/presensi_form', $data);
    }

    public function form($id_jadwal, $tanggal = null)
    {
        $data['judul'] = 'Form Presensi';
        
        $id_jadwal_decrypted = decrypt_id($id_jadwal);
        $data['jadwal'] = $this->M_jadwal->get_by_id($id_jadwal_decrypted);
        
        if (!$data['jadwal']) {
            show_403();
            return;
        }
        
        $data['tanggal'] = $tanggal ?: date('Y-m-d');
        $data['siswa'] = $this->M_presensi->get_siswa_by_kelas($data['jadwal']['id_kelas']);
        
        // Check if already submitted
        $existing = $this->M_presensi->get_presensi_by_jadwal_tanggal($id_jadwal_decrypted, $data['tanggal']);
        $data['existing_presensi'] = $existing;
        
        $this->render_template('guru/presensi_input', $data);
    }

    public function simpan()
    {
        $id_jadwal = decrypt_id($this->input->post('id_jadwal'));
        $tanggal = $this->input->post('tanggal');
        $materi = $this->input->post('materi_pelajaran');
        
        $this->form_validation->set_rules('materi_pelajaran', 'Materi Pelajaran', 'required|min_length[5]');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }
        
        // Get guru data
        $user_id = $this->session->userdata('id');
        $this->db->where('id_user', $user_id);
        $guru = $this->db->get('tb_guru')->row_array();
        
        $data_presensi = [];
        $data_approval = [];
        
        $siswa_ids = $this->input->post('id_siswa');
        $status_arr = $this->input->post('status');
        $keterangan_arr = $this->input->post('keterangan');
        
        foreach ($siswa_ids as $key => $id_siswa) {
            $status = $status_arr[$key];
            $keterangan = $keterangan_arr[$key] ?? '';
            
            // Validate keterangan for Izin/Sakit
            if (in_array($status, ['Izin', 'Sakit']) && empty($keterangan)) {
                echo json_encode(['status' => false, 'message' => 'Keterangan wajib diisi untuk status Izin/Sakit']);
                return;
            }
            
            if (strlen($keterangan) < 10 && in_array($status, ['Izin', 'Sakit'])) {
                echo json_encode(['status' => false, 'message' => 'Keterangan minimal 10 karakter untuk status Izin/Sakit']);
                return;
            }
            
            $presensi_data = [
                'id_jadwal' => $id_jadwal,
                'id_siswa' => $id_siswa,
                'tanggal' => $tanggal,
                'status' => $status,
                'materi_pelajaran' => $materi,
                'keterangan' => in_array($status, ['Hadir', 'Alpa']) ? null : $keterangan,
                'id_guru' => $guru['id'],
                'metode' => 'web'
            ];
            
            $data_presensi[] = $presensi_data;
            
            // Create approval record for Izin/Sakit
            if (in_array($status, ['Izin', 'Sakit'])) {
                $approval_data = [
                    'id_presensi' => 0, // Will be updated after insert - need to get from presensi header
                    'status_approval' => 'pending'
                ];
                $data_approval[] = $approval_data;
            }
        }
        
        // Save presensi with approval data
        $result = $this->M_presensi->simpan_presensi($data_presensi, $data_approval);
        
        if ($result['status']) {
            log_aktivitas('INSERT_PRESENSI', 'tb_presensi', $id_jadwal, 'Input presensi tanggal ' . $tanggal);
            
            echo json_encode(['status' => true, 'message' => 'Presensi berhasil disimpan']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menyimpan presensi']);
        }
    }
}