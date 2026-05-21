<?php
/**
 * Model untuk autentikasi dan manajemen user
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model {
    
    /**
     * Login user
     * @param string $username Username
     * @param string $password Password (plain text)
     * @return array|false User data jika berhasil, false jika gagal
     */
    public function login($username, $password) {
        // Get user dari database
        $this->db->where('username', $username);
        $this->db->where('status', 'aktif');
        $query = $this->db->get('tb_user', 1);
        
        if ($query->num_rows() === 0) {
            return false;
        }
        
        $user = $query->row();
        
        // Verify password
        if (!password_verify($password, $user->password)) {
            return false;
        }
        
        // Update last_login
        $this->db->where('id', $user->id);
        $this->db->update('tb_user', array('last_login' => date('Y-m-d H:i:s')));
        
        // Return user data tanpa password
        unset($user->password);
        return $user;
    }
    
    /**
     * Get user by ID
     * @param int $id User ID
     * @return object|null User data
     */
    public function get_user_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('tb_user', 1);
        
        if ($query->num_rows() === 0) {
            return null;
        }
        
        return $query->row();
    }
    
    /**
     * Update user profile
     * @param int $id User ID
     * @param array $data Data to update
     * @return bool
     */
    public function update_profile($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('tb_user', $data);
    }
    
    /**
     * Update password
     * @param int $id User ID
     * @param string $new_password New password (hashed)
     * @return bool
     */
    public function update_password($id, $new_password) {
        $this->db->where('id', $id);
        return $this->db->update('tb_user', array('password' => $new_password));
    }
    
    /**
     * Check if username exists
     * @param string $username Username
     * @param int|null $exclude_id Exclude this ID (for edit)
     * @return bool
     */
    public function username_exists($username, $exclude_id = null) {
        $this->db->where('username', $username);
        
        if ($exclude_id !== null) {
            $this->db->where('id !=', $exclude_id);
        }
        
        $query = $this->db->get('tb_user', 1);
        return $query->num_rows() > 0;
    }
}
