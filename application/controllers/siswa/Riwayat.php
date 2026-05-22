<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->role_required = ['siswa'];
        $this->load->model('siswa/M_riwayat');
    }

    public function index()
    {
        $this->data['judul'] = 'Riwayat Presensi';
        
        // Get siswa ID from session
        $user_id = $this->session->userdata('id');
        $this->db->where('id_user', $user_id);
        $siswa = $this->db->get('tb_siswa')->row_array();
        
        if (!$siswa) {
            $this->session->set_flashdata('error', 'Data siswa tidak ditemukan. Hubungi administrator.');
            redirect('profil');
            return;
        }
        
        $this->data['siswa'] = $siswa;
        $this->data['content'] = 'siswa/riwayat';
        
        $this->load->view('templates/template', $this->data);
    }

    public function ajax_list()
    {
        $user_id = $this->session->userdata('id');
        $this->db->where('id_user', $user_id);
        $siswa = $this->db->get('tb_siswa')->row_array();
        
        if (!$siswa) {
            echo json_encode(['status' => false, 'message' => 'Data siswa tidak ditemukan']);
            return;
        }
        
        // Get filter parameters from POST
        $tanggal_mulai = $this->input->post('tanggal_mulai') ?: date('Y-m-01');
        $tanggal_sampai = $this->input->post('tanggal_sampai') ?: date('Y-m-t');
        $status_filter = $this->input->post('status');
        
        $list = $this->M_riwayat->get_riwayat_siswa($siswa['id'], $tanggal_mulai, $tanggal_sampai, $status_filter);
        
        $output = [
            'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
            'recordsTotal' => count($list),
            'recordsFiltered' => count($list),
            'data' => []
        ];
        
        $no = 0;
        foreach ($list as $row) {
            $no++;
            
            $status_badge = '';
            switch ($row['status']) {
                case 'Hadir': $status_badge = '<span class="badge bg-success">Hadir</span>'; break;
                case 'Izin': $status_badge = '<span class="badge bg-info">Izin</span>'; break;
                case 'Sakit': $status_badge = '<span class="badge bg-warning text-dark">Sakit</span>'; break;
                case 'Alpa': $status_badge = '<span class="badge bg-danger">Alpa</span>'; break;
                default: $status_badge = '<span class="badge bg-secondary">-</span>'; break;
            }
            
            $approval_status = '-';
            if (in_array($row['status'], ['Izin', 'Sakit'])) {
                if ($row['status_approval'] == 'disetujui') {
                    $approval_status = '<span class="badge bg-success"><i class="fas fa-check"></i> Disetujui</span>';
                } else if ($row['status_approval'] == 'ditolak') {
                    $approval_status = '<span class="badge bg-danger"><i class="fas fa-times"></i> Ditolak</span>';
                } else {
                    $approval_status = '<span class="badge bg-warning"><i class="fas fa-clock"></i> Pending</span>';
                }
            }
            
            $output['data'][] = [
                $no,
                tanggal_indo($row['tanggal']),
                $row['hari'],
                $row['nama_mapel'],
                $row['nama_guru'] ?? '-',
                substr($row['materi_pelajaran'] ?? '', 0, 40) . (strlen($row['materi_pelajaran'] ?? '') > 40 ? '...' : ''),
                $status_badge,
                $row['keterangan'] ?? '-',
                $approval_status
            ];
        }
        
        echo json_encode($output);
    }

    public function ajax_statistik()
    {
        $user_id = $this->session->userdata('id');
        $this->db->where('id_user', $user_id);
        $siswa = $this->db->get('tb_siswa')->row_array();
        
        if (!$siswa) {
            echo json_encode(['status' => false, 'message' => 'Data siswa tidak ditemukan']);
            return;
        }
        
        $statistik = $this->M_riwayat->get_statistik_bulan_ini($siswa['id']);
        
        echo json_encode([
            'status' => true,
            'hadir' => $statistik['hadir'] ?? 0,
            'izin' => $statistik['izin'] ?? 0,
            'sakit' => $statistik['sakit'] ?? 0,
            'alpa' => $statistik['alpa'] ?? 0
        ]);
    }
}
