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
        
        // Cek akses kepsek
        if (!$this->cek_akses(array('kepsek'))) {
            show_403();
        }
        
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
        $this->data['content'] = 'kepsek/logapproval';
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
            $row = array();
            $row[] = $no;
            $row[] = tanggal_indo($item->tanggal_approval, true);
            $row[] = $item->nama_siswa;
			$row[] = $item->nama_kelas ?? '-';
            $row[] = badge_presensi($item->status_presensi);
            $row[] = badge_approval($item->status_approval);
			
            $row[] = $item->catatan ?? '-';
            $row[] = $item->nama_approver ?? 'System';
            
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_logapproval->count_all(),
            "recordsFiltered" => $this->M_logapproval->count_filtered(),
            "data" => $data,
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
}
