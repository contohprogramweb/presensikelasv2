<?php
/**
 * MY_Controller - Base Controller untuk semua controller
 * Menangani autentikasi, authorization, dan setup global
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    /**
     * @var array Role yang diizinkan mengakses controller ini
     */
    protected $role_required = array();
    
    /**
     * @var object Data tahun ajaran aktif
     */
    protected $tahun_ajaran_aktif;
    
    /**
     * @var array Data global yang akan dikirim ke view
     */
    protected $data = array();
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Load helper yang diperlukan
        $this->load->helper(array('url', 'form', 'security', 'custom'));
        
        // Cek apakah user sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
            return;
        }
        
        // Cek idle timeout (30 menit)
        $last_activity = $this->session->userdata('last_activity');
        $idle_time = 1800; // 30 menit dalam detik
        
        if ($last_activity && (time() - $last_activity > $idle_time)) {
            $this->session->sess_destroy();
            $this->session->set_flashdata('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
            redirect('auth/login');
            return;
        }
        
        // Update last activity
        $this->session->set_userdata('last_activity', time());
        
        // Cek role authorization
        if (!empty($this->role_required)) {
            $user_role = $this->session->userdata('role');
            
            if (!in_array($user_role, $this->role_required)) {
                show_403();
                return;
            }
        }
        
        // Load tahun ajaran aktif
        $this->load->model('M_dashboard');
        $this->tahun_ajaran_aktif = $this->M_dashboard->get_tahun_ajaran_aktif();
        
        if (!$this->tahun_ajaran_aktif) {
            // Jika tidak ada tahun ajaran aktif, ambil yang pertama
            $this->tahun_ajaran_aktif = $this->M_dashboard->get_tahun_ajaran_terbaru();
        }
        
        // Set data global untuk view
        $this->data['tahun_ajaran'] = $this->tahun_ajaran_aktif;
        $this->data['user_data'] = array(
            'id' => $this->session->userdata('id'),
            'username' => $this->session->userdata('username'),
            'nama_lengkap' => $this->session->userdata('nama_lengkap'),
            'role' => $this->session->userdata('role'),
            'foto_profil' => $this->session->userdata('foto_profil')
        );
        
        // Set CSRF token untuk AJAX
        $this->data['csrf_name'] = $this->security->get_csrf_token_name();
        $this->data['csrf_hash'] = $this->security->get_csrf_hash();
    }
    
    /**
     * Render view dengan template
     * @param string $view Nama view file
     * @param array $data Data untuk view
     * @param bool $return Whether to return the output instead of displaying it
     * @return string|void
     */
    protected function render($view, $data = array(), $return = false) {
        $data = array_merge($this->data, $data);
        
        if ($return) {
            return $this->load->view($view, $data, true);
        }
        
        $this->load->view($view, $data);
    }
    
    /**
     * Render view dengan template full (header, sidebar, footer)
     * @param string $content_view Nama view content
     * @param array $data Data untuk view
     * @param string $template Template file (default: template)
     */
    protected function render_template($content_view, $data = array(), $template = 'templates/template') {
        $data = array_merge($this->data, $data);
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view($content_view, $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Return JSON response
     * @param array $data Data to return
     * @param int $status_code HTTP status code
     */
    protected function json_response($data, $status_code = 200) {
        $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
    
    /**
     * Get current user ID
     * @return int
     */
    protected function get_user_id() {
        return $this->session->userdata('id');
    }
    
    /**
     * Get current user role
     * @return string
     */
    protected function get_user_role() {
        return $this->session->userdata('role');
    }
}

/**
 * Controller khusus untuk error pages
 */
class Error_Controller extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Handle 403 Forbidden error
     */
    public function error_403() {
        $this->output->set_status_header(403);
        $this->load->view('errors/html/error_403');
    }
    
    /**
     * Handle 404 Not Found error
     */
    public function error_404() {
        $this->output->set_status_header(404);
        $this->load->view('errors/html/error_404');
    }
    
    /**
     * Handle 500 Internal Server Error
     */
    public function error_500() {
        $this->output->set_status_header(500);
        $this->load->view('errors/html/error_500');
    }
}
