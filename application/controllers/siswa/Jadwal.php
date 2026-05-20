<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller: Siswa - Jadwal Pelajaran
 */
class Jadwal extends MY_Controller {
    
    protected $role_required = ['siswa'];
    
    public function __construct() {
        parent::__construct();
        $this->load->model('siswa/M_jadwal');
    }
    
    /**
     * Halaman jadwal pelajaran siswa
     */
    public function index() {
        $this->data['page_title'] = 'Jadwal Pelajaran';
        
        // Get id_siswa dari tb_siswa berdasarkan session user
        $id_user = $this->session->userdata('id');
        $this->db->where('id_user', $id_user);
        $siswa = $this->db->get('tb_siswa')->row();
        
        if (!$siswa || !$siswa->id_kelas) {
            $this->data['error_message'] = 'Anda belum ditempatkan di kelas. Hubungi admin.';
            $this->data['jadwal'] = [];
            $this->data['jadwal_grouped'] = [];
        } else {
            $this->data['jadwal'] = $this->M_jadwal->get_jadwal_kelas($siswa->id_kelas);
            
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
        }
        
        $this->data['hari_list'] = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        $this->load->view('templates/template', 'siswa/jadwal', $this->data);
    }
}
