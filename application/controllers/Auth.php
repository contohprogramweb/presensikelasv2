<?php
/**
 * Controller untuk autentikasi (login/logout)
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('M_auth');
        $this->load->helper('form');
        
        // Redirect to dashboard if already logged in
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
    }
    
    /**
     * Login page and process
     */
    public function login() {
        $data = array(
            'title' => 'Login - Sistem Presensi Kelas',
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        
        // If POST, process login
        if ($this->input->method() === 'post') {
            $username = $this->input->post('username', true);
            $password = $this->input->post('password');
            
            // Validation
            $this->form_validation->set_rules('username', 'Username', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required');
            
            if ($this->form_validation->run() == false) {
                // Validation failed, show form with errors
                $this->load->view('auth/login', $data);
            } else {
                // Try to login
                $user = $this->M_auth->login($username, $password);
                
                if ($user) {
                    // Login successful
                    $session_data = array(
                        'id' => $user->id,
                        'username' => $user->username,
                        'nama_lengkap' => $user->nama_lengkap,
                        'role' => $user->role,
                        'foto_profil' => $user->foto_profil,
                        'email' => $user->email,
                        'logged_in' => true,
                        'last_activity' => time()
                    );
                    
                    $this->session->set_userdata($session_data);
                    $this->session->regenerate_id();
                    
                    // Log activity
                    $this->load->model('M_logaktivitas');
                    log_aktivitas('login', 'tb_user', $user->id, 'User login berhasil');
                    
                    // Redirect based on role
                    redirect('dashboard');
                } else {
                    // Login failed
                    $this->session->set_flashdata('error', 'Username atau password salah');
                    redirect('auth/login');
                }
            }
        } else {
            // Show login form
            $this->load->view('auth/login', $data);
        }
    }
    
    /**
     * Logout
     */
    public function logout() {
        // Log activity before logout
        if ($this->session->userdata('logged_in')) {
            log_aktivitas('logout', 'tb_user', $this->session->userdata('id'), 'User logout');
        }
        
        // Destroy session
        $this->session->sess_destroy();
        
        // Redirect to login
        redirect('auth/login');
    }
}
