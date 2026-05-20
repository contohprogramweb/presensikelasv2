<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller: Guru - Jadwal Mengajar
 */
class Jadwal extends MY_Controller {
    
    protected $role_required = ['guru'];
    
    public function __construct() {
        parent::__construct();
        $this->load->model('guru/M_jadwal');
    }
    
    /**
     * Halaman jadwal mengajar guru
     */
    public function index() {
        $this->data['page_title'] = 'Jadwal Mengajar';
        $this->data['jadwal'] = $this->M_jadwal->get_jadwal_guru($this->session->userdata('id'));
        
        // Group by hari
        $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jadwal_grouped = [];
        
        foreach ($hari_list as $hari) {
            $jadwal_grouped[$hari] = [];
        }
        
        foreach ($this->data['jadwal'] as $j) {
            $jadwal_grouped[$j->hari][] = $j;
        }
        
        $this->data['jadwal_grouped'] = $jadwal_grouped;
        $this->data['hari_list'] = $hari_list;
        
        $this->load->view('templates/template', 'guru/jadwal', $this->data);
    }
}
