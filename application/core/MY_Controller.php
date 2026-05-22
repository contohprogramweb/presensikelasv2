<?php
/**
 * MY_Controller - Base Controller untuk semua controller
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $role_required  = array();
    protected $tahun_ajaran_aktif;
    protected $data           = array();

    public function __construct() {
        parent::__construct();

        $this->load->helper(array('url', 'form', 'security', 'custom'));

        // Deteksi apakah request ini adalah AJAX
        $is_ajax = $this->input->is_ajax_request();

        // ── Cek login ──────────────────────────────────────────────
        if (!$this->session->userdata('logged_in')) {
            if ($is_ajax) {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status'  => false,
                        'message' => 'Sesi telah berakhir. Silakan refresh halaman dan login kembali.',
                        'expired' => true,
                    ]));
                exit;
            }
            redirect('auth/login');
            exit;
        }

        // ── Cek idle timeout (30 menit) ────────────────────────────
        $last_activity = $this->session->userdata('last_activity');
        $idle_time     = 1800;

        if ($last_activity && (time() - $last_activity > $idle_time)) {
            $this->session->sess_destroy();

            if ($is_ajax) {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status'  => false,
                        'message' => 'Sesi Anda telah berakhir karena tidak aktif. Silakan login kembali.',
                        'expired' => true,
                    ]));
                exit;
            }
            $this->session->set_flashdata('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
            redirect('auth/login');
            exit;
        }

        // Update last activity
        $this->session->set_userdata('last_activity', time());

        // ── Cek role ───────────────────────────────────────────────
        if (!empty($this->role_required)) {
            $user_role = $this->session->userdata('role');
            if (!in_array($user_role, $this->role_required)) {
                if ($is_ajax) {
                    $this->output
                        ->set_status_header(403)
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            'status'  => false,
                            'message' => 'Akses ditolak.',
                        ]));
                    exit;
                }
                show_403();
                exit;
            }
        }

        // ── Load tahun ajaran aktif ────────────────────────────────
        $this->load->model('M_dashboard');
        $this->tahun_ajaran_aktif = $this->M_dashboard->get_tahun_ajaran_aktif();

        if (!$this->tahun_ajaran_aktif) {
            $this->tahun_ajaran_aktif = $this->M_dashboard->get_tahun_ajaran_terbaru();
        }

        // ── Data global untuk view ─────────────────────────────────
        $this->data['tahun_ajaran'] = $this->tahun_ajaran_aktif;
        $this->data['user_data']    = array(
            'id'           => $this->session->userdata('id'),
            'username'     => $this->session->userdata('username'),
            'nama_lengkap' => $this->session->userdata('nama_lengkap'),
            'role'         => $this->session->userdata('role'),
            'foto_profil'  => $this->session->userdata('foto_profil'),
        );
        $this->data['csrf_name'] = $this->security->get_csrf_token_name();
        $this->data['csrf_hash'] = $this->security->get_csrf_hash();
    }

    protected function render($view, $data = array(), $return = false) {
        $data = array_merge($this->data, $data);
        if ($return) {
            return $this->load->view($view, $data, true);
        }
        $this->load->view($view, $data);
    }

    protected function render_template($content_view, $data = array()) {
        $data = array_merge($this->data, $data);
        $this->load->view('templates/header',  $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view($content_view,       $data);
        $this->load->view('templates/footer');
    }

    protected function json_response($data, $status_code = 200) {
        $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    protected function get_user_id() {
        return $this->session->userdata('id');
    }

    protected function get_user_role() {
        return $this->session->userdata('role');
    }
}

class Error_Controller extends CI_Controller {
    public function __construct() { parent::__construct(); }
    public function error_403() { $this->output->set_status_header(403); $this->load->view('errors/html/error_403'); }
    public function error_404() { $this->output->set_status_header(404); $this->load->view('errors/html/error_404'); }
    public function error_500() { $this->output->set_status_header(500); $this->load->view('errors/html/error_500'); }
}
