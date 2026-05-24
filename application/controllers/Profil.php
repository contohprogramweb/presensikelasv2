<?php
/**
 * Controller untuk Profil User
 */

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Controller.php';

class Profil extends MY_Controller {
    
    /**
     * @var array All roles can access profile
     */
    protected $role_required = array('admin', 'guru', 'siswa', 'kepsek');
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('M_profil');
    }
    
    /**
     * Index - Show profile form
     */
    public function index() {
        $user_id = $this->get_user_id();
        $profile = $this->M_profil->get_profile($user_id);
        
        if (!$profile) {
            $this->session->set_flashdata('error', 'Data profil tidak ditemukan');
            redirect('dashboard');
            return;
        }
        
        $data = array(
            'title' => 'Profil Saya',
            'profile' => $profile
        );
        
        $this->render_template('profil/index', $data);
    }
    
    /**
     * Update profile (AJAX/POST)
     */
    public function update_profil() {
        // Only accept POST
        if ($this->input->method() !== 'post') {
            $this->json_response(array('status' => false, 'message' => 'Method not allowed'), 405);
            return;
        }
        
        $user_id = $this->get_user_id();
        
        // Validation
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'valid_email|trim|max_length[100]');
        $this->form_validation->set_rules('no_hp', 'No HP', 'numeric|trim|max_length[20]');
        
        if ($this->form_validation->run() == false) {
            $errors = validation_errors();
            $this->json_response(array('status' => false, 'message' => $errors), 400);
            return;
        }
        
        // Prepare data
        $data = array(
            'nama_lengkap' => $this->input->post('nama_lengkap', true),
            'email' => $this->input->post('email', true),
            'no_hp' => $this->input->post('no_hp', true)
        );
        
        // Handle file upload
        if (!empty($_FILES['foto_profil']['name'])) {
            $config['upload_path'] = './assets/uploads/profil/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = $this->session->userdata('username') . '_' . time();
            
            // Ensure upload directory exists
            if (!file_exists($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, true);
            }
            
            $this->load->library('upload', $config);
            
            if (!$this->upload->do_upload('foto_profil')) {
                $error = $this->upload->display_errors(' ', ' ');
                $this->json_response(array('status' => false, 'message' => $error), 400);
                return;
            }
            
            $upload_data = $this->upload->data();
            
            // Additional MIME type validation for security
            $allowed_mime_types = array('image/jpeg', 'image/png', 'image/jpg');
            
            // Try to use finfo if available, otherwise use getimagesize
            if (function_exists('finfo_open')) {
                $file_info = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $file_info->file($config['upload_path'] . $upload_data['file_name']);
            } else {
                $image_info = getimagesize($config['upload_path'] . $upload_data['file_name']);
                $mime_type = $image_info['mime'];
            }
            
            if (!in_array($mime_type, $allowed_mime_types)) {
                // Delete invalid file
                unlink($config['upload_path'] . $upload_data['file_name']);
                $this->json_response(array('status' => false, 'message' => 'Tipe file tidak diizinkan. Hanya JPG dan PNG yang diperbolehkan.'), 400);
                return;
            }
            
            // Get old photo to delete
            $old_photo = $this->M_profil->get_old_photo($user_id);
            if ($old_photo && file_exists($config['upload_path'] . $old_photo)) {
                unlink($config['upload_path'] . $old_photo);
            }
            
            $data['foto_profil'] = $upload_data['file_name'];
            
            // Resize image to 300x300
            $this->resize_image($config['upload_path'] . $upload_data['file_name'], 300, 300);
        }
        
        // Update database - STRICT: only update current user
        $this->db->where('id', $user_id);
        $update = $this->db->update('tb_user', $data);
        
        if ($update) {
            // Update session
            $this->session->set_userdata(array(
                'nama_lengkap' => $data['nama_lengkap'],
                'foto_profil' => isset($data['foto_profil']) ? $data['foto_profil'] : $this->session->userdata('foto_profil')
            ));
            
            // Log activity
            log_aktivitas('update_profil', 'tb_user', $user_id, 'Update profil user');
            
            $foto_url = base_url('assets/uploads/profil/' . (isset($data['foto_profil']) ? $data['foto_profil'] : ''));
            
            $this->json_response(array(
                'status' => true,
                'message' => 'Profil berhasil diperbarui',
                'foto_url' => $foto_url
            ));
        } else {
            $this->json_response(array('status' => false, 'message' => 'Gagal memperbarui profil'), 500);
        }
    }
    
    /**
     * Update password (AJAX/POST)
     */
    public function update_password() {
        // Only accept POST
        if ($this->input->method() !== 'post') {
            $this->json_response(array('status' => false, 'message' => 'Method not allowed'), 405);
            return;
        }
        
        $user_id = $this->get_user_id();
        
        // Validation
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[8]|regex_match[/^(?=.*[a-zA-Z])(?=.*[0-9])/]');
        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'required|matches[password_baru]');
        
        if ($this->form_validation->run() == false) {
            $errors = validation_errors();
            $this->json_response(array('status' => false, 'message' => $errors), 400);
            return;
        }
        
        $password_lama = $this->input->post('password_lama');
        $password_baru = $this->input->post('password_baru');
        
        // Verify old password - STRICT: verify against current user
        if (!$this->M_profil->verify_password($user_id, $password_lama)) {
            $this->json_response(array('status' => false, 'message' => 'Password lama salah'), 400);
            return;
        }
        
        // Hash new password
        $password_hash = password_hash($password_baru, PASSWORD_BCRYPT);
        
        // Update database - STRICT: only update current user
        $this->db->where('id', $user_id);
        $update = $this->db->update('tb_user', array('password' => $password_hash));
        
        if ($update) {
            // Log activity
            log_aktivitas('update_password', 'tb_user', $user_id, 'Update password user');
            
            $this->json_response(array('status' => true, 'message' => 'Password berhasil diubah'));
        } else {
            $this->json_response(array('status' => false, 'message' => 'Gagal mengubah password'), 500);
        }
    }
    
    /**
     * Resize image using GD library
     * @param string $source_path Path to source image
     * @param int $width Target width
     * @param int $height Target height
     * @return bool
     */
    private function resize_image($source_path, $width, $height) {
        $image_info = getimagesize($source_path);
        $mime = $image_info['mime'];
        
        switch ($mime) {
            case 'image/jpeg':
                $source = imagecreatefromjpeg($source_path);
                break;
            case 'image/png':
                $source = imagecreatefrompng($source_path);
                break;
            default:
                return false;
        }
        
        if (!$source) {
            return false;
        }
        
        $source_width = imagesx($source);
        $source_height = imagesy($source);
        
        // Create new image
        $target = imagecreatetruecolor($width, $height);
        
        // Preserve transparency for PNG
        if ($mime === 'image/png') {
            imagealphablending($target, false);
            imagesavealpha($target, true);
        }
        
        // Resize
        imagecopyresampled($target, $source, 0, 0, 0, 0, $width, $height, $source_width, $source_height);
        
        // Save
        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($target, $source_path, 90);
                break;
            case 'image/png':
                imagepng($target, $source_path, 9);
                break;
        }
        
        imagedestroy($source);
        imagedestroy($target);
        
        return true;
    }
}
