<?php
/**
 * Controller untuk Dashboard (role-based)
 */

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Controller.php';

class Dashboard extends MY_Controller {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('M_dashboard');
    }
    
    /**
     * Index - redirect based on role
     */
    public function index() {
        $role = $this->session->userdata('role');
        
        switch ($role) {
            case 'admin':
                $this->admin();
                break;
            case 'guru':
                $this->guru();
                break;
            case 'kepsek':
                $this->kepsek();
                break;
            case 'siswa':
                $this->siswa();
                break;
            default:
                show_403();
        }
    }
    
    /**
     * Admin dashboard
     */
    private function admin() {
        $tahun_ajaran_id = $this->tahun_ajaran_aktif ? $this->tahun_ajaran_aktif->id : null;
        $stats = $this->M_dashboard->get_admin_stats($tahun_ajaran_id);
        
        $data = array(
            'title' => 'Dashboard Admin',
            'stats' => $stats
        );
        
	 
		 
        $this->render_template('admin/dashboard', $data);
    }
    
    /**
     * Guru dashboard
     */
    private function guru() {
        // Get guru data from session
        $user_id = $this->session->userdata('id');
        
        $this->db->where('id_user', $user_id);
        $guru = $this->db->get('tb_guru', 1)->row();
        
        if (!$guru) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan. Hubungi administrator.');
            redirect('profil');
            return;
        }
        
        $stats = $this->M_dashboard->get_guru_stats($guru->id);
        
        $data = array(
            'title' => 'Dashboard Guru',
            'stats' => $stats,
            'guru' => $guru
        );
        
        $this->render_template('guru/dashboard', $data);
    }
    
    /**
     * Kepala Sekolah dashboard
     */
    private function kepsek() {
        $stats = $this->M_dashboard->get_kepsek_stats();
        
        $data = array(
            'title' => 'Dashboard Kepala Sekolah',
            'stats' => $stats
        );
        
        $this->render_template('kepsek/dashboard', $data);
    }
    
    /**
     * Siswa dashboard
     */
    private function siswa() {
        $user_id = $this->session->userdata('id');
        
        $this->db->where('id_user', $user_id);
        $siswa = $this->db->get('tb_siswa', 1)->row();
        
        if (!$siswa) {
            $this->session->set_flashdata('error', 'Data siswa tidak ditemukan. Hubungi administrator.');
            redirect('profil');
            return;
        }
        
        $stats = $this->M_dashboard->get_siswa_stats($siswa->id);
        
        // Check if student is assigned to a class
        $not_assigned = is_null($siswa->id_kelas);
        
        $data = array(
            'title' => 'Dashboard Siswa',
            'stats' => $stats,
            'siswa' => $siswa,
            'not_assigned' => $not_assigned
        );
        
        $this->render_template('siswa/dashboard', $data);
    }
}
