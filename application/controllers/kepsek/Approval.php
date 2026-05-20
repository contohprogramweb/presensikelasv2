<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->role_required = ['kepsek'];
        $this->load->model('kepsek/M_approval');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['judul'] = 'Approval Presensi';
        $this->load->view('templates/template', ['contents' => $this->load->view('kepsek/approval', $data, TRUE)]);
    }

    public function ajax_list()
    {
        $list = $this->M_approval->get_pending_approval();
        
        $output = [
            'draw' => 0,
            'recordsTotal' => count($list),
            'recordsFiltered' => count($list),
            'data' => []
        ];
        
        foreach ($list as $row) {
            $status_badge = '';
            if ($row['status_presensi'] == 'Izin') {
                $status_badge = '<span class="badge bg-info">Izin</span>';
            } else if ($row['status_presensi'] == 'Sakit') {
                $status_badge = '<span class="badge bg-warning text-dark">Sakit</span>';
            }
            
            $output['data'][] = [
                tanggal_indo($row['tanggal']),
                $row['nama_siswa'],
                $row['nama_kelas'] ?? '-',
                $row['nama_guru'],
                $status_badge,
                substr($row['keterangan'], 0, 50) . (strlen($row['keterangan']) > 50 ? '...' : ''),
                '<button class="btn btn-sm btn-success approve-btn" data-id="' . encrypt_id($row['id']) . '"><i class="fas fa-check"></i></button>
                 <button class="btn btn-sm btn-danger reject-btn" data-id="' . encrypt_id($row['id']) . '"><i class="fas fa-times"></i></button>'
            ];
        }
        
        echo json_encode($output);
    }

    public function approve()
    {
        $id = decrypt_id($this->input->post('id'));
        $id_approver = $this->session->userdata('id');
        
        if ($this->M_approval->approve($id, $id_approver)) {
            log_aktivitas('APPROVE_PRESENSI', 'tb_approval', $id, 'Approval disetujui');
            echo json_encode(['status' => true, 'message' => 'Presensi berhasil disetujui']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menyetujui presensi']);
        }
    }

    public function reject()
    {
        $id = decrypt_id($this->input->post('id'));
        $id_approver = $this->session->userdata('id');
        $catatan = $this->input->post('catatan');
        
        if (empty($catatan)) {
            echo json_encode(['status' => false, 'message' => 'Catatan penolakan wajib diisi']);
            return;
        }
        
        if ($this->M_approval->reject($id, $id_approver, $catatan)) {
            log_aktivitas('REJECT_PRESENSI', 'tb_approval', $id, 'Approval ditolak: ' . $catatan);
            echo json_encode(['status' => true, 'message' => 'Presensi berhasil ditolak']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menolak presensi']);
        }
    }
}
