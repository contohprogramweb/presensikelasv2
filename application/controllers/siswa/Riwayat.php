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
        
        // Set default filter dates
        $tanggal_mulai_default = date('Y-m-01'); // Tanggal 01 bulan ini
        $tanggal_sampai_default = date('Y-m-d');  // Tanggal hari ini
        
        $this->data['tanggal_mulai_default'] = $tanggal_mulai_default;
        $this->data['tanggal_sampai_default'] = $tanggal_sampai_default;
        
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
		
		 // Pastikan ini adalah request AJAX
        if (!$this->input->is_ajax_request()) {
            show_error('Akses tidak diizinkan', 403);
            return;
        }
		
        try {
            $user_id = $this->session->userdata('id');
            $this->db->where('id_user', $user_id);
            $siswa = $this->db->get('tb_siswa')->row_array();
            
            if (!$siswa) {
                echo json_encode([
                    'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Data siswa tidak ditemukan'
                ]);
                return;
            }
            
            log_message('debug', 'Riwayat::ajax_list - Siswa ID: ' . $siswa['id']);
            
            // Get filter parameters from POST with default values
            $tanggal_mulai = $this->input->post('tanggal_mulai') ?: date('Y-m-01'); // Default: tanggal 01 bulan ini
            $tanggal_sampai = $this->input->post('tanggal_sampai') ?: date('Y-m-d'); // Default: tanggal hari ini
            $status_filter = $this->input->post('status');
            
            log_message('debug', 'Riwayat::ajax_list - Filter: tanggal_mulai=' . $tanggal_mulai . ', tanggal_sampai=' . $tanggal_sampai . ', status=' . $status_filter);
            
            // Server-side processing parameters
            $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
            $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
            $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
            $order = isset($_POST['order']) ? $_POST['order'] : [];
            $search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
            
            // Get total records
            $total_records = $this->M_riwayat->count_all_riwayat($siswa['id'], $tanggal_mulai, $tanggal_sampai, null, '');
            
            // Get filtered records
            $filtered_records = $this->M_riwayat->count_all_riwayat($siswa['id'], $tanggal_mulai, $tanggal_sampai, $status_filter, $search);
            
            // Get data
            $list = $this->M_riwayat->get_riwayat_siswa_datatable(
                $siswa['id'], 
                $tanggal_mulai, 
                $tanggal_sampai, 
                $status_filter, 
                $search,
                $length,
                $start,
                $order
            );
            
            log_message('debug', 'Riwayat::ajax_list - Total records: ' . $total_records . ', Filtered: ' . $filtered_records . ', Returned: ' . count($list));
            
            $output = [
                'draw' => $draw,
                'recordsTotal' => $total_records,
                'recordsFiltered' => $filtered_records,
                'data' => []
            ];
            
            $no = $start;
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
                if (isset($row['status_approval']) && in_array($row['status'], ['Izin', 'Sakit'])) {
                    if ($row['status_approval'] == 'disetujui') {
                        $approval_status = '<span class="badge bg-success"><i class="fas fa-check"></i> Disetujui</span>';
                    } else if ($row['status_approval'] == 'ditolak') {
                        $approval_status = '<span class="badge bg-danger"><i class="fas fa-times"></i> Ditolak</span>';
                    } else {
                        $approval_status = '<span class="badge bg-warning"><i class="fas fa-clock"></i> Pending</span>';
                    }
                }
                 
				$materi = !empty($row['materi_pelajaran']) ? $row['materi_pelajaran'] : '-';
                $keterangan = !empty($row['catatan_approval']) ? $row['catatan_approval'] : '-';
                
                $output['data'][] = [
                    'no' => $no,
                    'tanggal' => tanggal_indo($row['tanggal']),
                    'hari' => $row['hari'] ?? '-',
                    'nama_mapel' => $row['nama_mapel'] ?? '-',
                    'nama_guru' => $row['nama_guru'] ?? '-',
                    'materi' => substr($materi, 0, 40) . (strlen($materi) > 40 ? '...' : ''),
                    'status' => $status_badge,
                    'keterangan' => $keterangan,
                    'status_approval' => $approval_status
                ];
            }
            
            echo json_encode($output);
            
        } catch (Exception $e) {
            log_message('error', 'Error in ajax_list: ' . $e->getMessage());
            echo json_encode([
                'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
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