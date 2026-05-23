<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Log Approval - Kepala Sekolah
 * Melihat riwayat approval presensi
 * 
 * @package     CodeIgniter
 * @subpackage  Controllers
 * @category    Kepsek
 */
class Logapproval extends MY_Controller {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        $this->role_required = ['kepsek'];
        
        // Load model
        $this->load->model('kepsek/M_logapproval');
        
        // Set data global
        $this->data['page_title'] = 'Log Approval Presensi';
        $this->data['active_menu'] = 'logapproval';
    }

    /**
     * Halaman utama log approval
     * 
     * @return void
     */
    public function index() {
        // Set default filter: awal bulan sampai hari ini
        $tanggal_mulai = date('Y-m-01');
        $tanggal_sampai = date('Y-m-d');
        
        $this->data['default_tanggal_mulai'] = $tanggal_mulai;
        $this->data['default_tanggal_sampai'] = $tanggal_sampai;
        
        $this->data['content'] = 'kepsek/logapproval';
        $this->data['csrf_name'] = $this->security->get_csrf_token_name();
        $this->data['csrf_hash'] = $this->security->get_csrf_hash();
        $this->load->view('templates/template', $this->data);
    }

    /**
     * AJAX: Get list log approval untuk DataTables
     * 
     * @return void
     */
    public function ajax_list() {
        $list = $this->M_logapproval->get_datatables();
        $data = array();
        $no = $_POST['start'];
        
        foreach ($list as $item) {
            $no++;
            $row = array(
                'no' => $no,
                'tanggal_approval' => tanggal_indo($item->tanggal_approval),
                'nama_siswa' => $item->nama_siswa,
                'nama_kelas' => $item->nama_kelas ?? '-',
                'status_presensi' => badge_presensi($item->status_presensi),
                'status_approval' => badge_approval($item->status_approval),
                'catatan' => $item->catatan ?? '-',
                'nama_approver' => $item->nama_approver ?? 'System'
            );
            
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_logapproval->count_all(),
            "recordsFiltered" => $this->M_logapproval->count_filtered(),
            "data" => $data,
            "csrf_hash" => $this->security->get_csrf_hash()
        );
        
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * AJAX: Export log to Excel
     * 
     * @return void
     */
    public function export_excel() {
        $filter = array(
            'tanggal_mulai' => $this->input->get('tanggal_mulai'),
            'tanggal_sampai' => $this->input->get('tanggal_sampai'),
            'status' => $this->input->get('status')
        );
        
        $data['logs'] = $this->M_logapproval->get_all_logs($filter);
        $data['page_title'] = 'Log Approval Presensi';
        $data['filter_info'] = $filter;
        
        // Load view untuk export
        $html = $this->load->view('kepsek/logapproval_excel', $data, TRUE);
        
        $this->load->library('dompdf_generator');
        $this->dompdf_generator->generate($html, 'log_approval_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Preview PDF log approval
     * 
     * @return void
     */
    public function preview_pdf() {
        $filter = array(
            'tanggal_mulai' => $this->input->get('tanggal_mulai'),
            'tanggal_sampai' => $this->input->get('tanggal_sampai'),
            'status' => $this->input->get('status')
        );
        
        $data['logs'] = $this->M_logapproval->get_all_logs($filter);
        $data['page_title'] = 'Preview Log Approval Presensi';
        $data['filter_info'] = $filter;
        
        // Load view untuk preview PDF
        $html = $this->load->view('kepsek/logapproval_excel', $data, TRUE);
        
        $this->load->library('dompdf_generator');
        $this->dompdf_generator->preview($html);
    }
}
