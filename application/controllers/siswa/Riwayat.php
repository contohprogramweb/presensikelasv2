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
        $data['judul'] = 'Riwayat Presensi';
        
        // Get siswa ID from session
        $user_id = $this->session->userdata('id');
        $this->db->where('id_user', $user_id);
        $siswa = $this->db->get('tb_siswa')->row_array();
        
        if (!$siswa) {
            $this->session->set_flashdata('error', 'Data siswa tidak ditemukan. Hubungi administrator.');
            redirect('profil');
            return;
        }
        
        $data['siswa'] = $siswa;
        $this->load->view('templates/template', ['contents' => $this->load->view('siswa/riwayat', $data, TRUE)]);
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
        
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');
        
        $list = $this->M_riwayat->get_riwayat_siswa($siswa['id'], $start_date, $end_date);
        
        $output = [
            'draw' => 0,
            'recordsTotal' => count($list),
            'recordsFiltered' => count($list),
            'data' => []
        ];
        
        foreach ($list as $row) {
            $status_badge = '';
            switch ($row['status']) {
                case 'Hadir': $status_badge = '<span class="badge bg-success">Hadir</span>'; break;
                case 'Izin': $status_badge = '<span class="badge bg-info">Izin</span>'; break;
                case 'Sakit': $status_badge = '<span class="badge bg-warning text-dark">Sakit</span>'; break;
                case 'Alpa': $status_badge = '<span class="badge bg-danger">Alpa</span>'; break;
            }
            
            $approval_status = '';
            if (in_array($row['status'], ['Izin', 'Sakit'])) {
                if ($row['status_approval'] == 'disetujui') {
                    $approval_status = '<span class="badge bg-success ms-1"><i class="fas fa-check"></i></span>';
                } else if ($row['status_approval'] == 'ditolak') {
                    $approval_status = '<span class="badge bg-danger ms-1"><i class="fas fa-times"></i></span>';
                } else {
                    $approval_status = '<span class="badge bg-secondary ms-1"><i class="fas fa-clock"></i></span>';
                }
            }
            
            $output['data'][] = [
                tanggal_indo($row['tanggal']),
                $row['hari'],
                $row['nama_mapel'],
                substr($row['materi_pelajaran'], 0, 40) . (strlen($row['materi_pelajaran']) > 40 ? '...' : ''),
                $status_badge . $approval_status,
                $row['keterangan'] ?? '-'
            ];
        }
        
        echo json_encode($output);
    }
}
