<?php
/**
 * Model untuk profil user
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class M_profil extends CI_Model {
    
    /**
     * Get user profile by ID
     * @param int $id User ID
     * @return object|null User data
     */
    public function get_profile($id) {
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
     * Verify password
     * @param int $id User ID
     * @param string $password Password to verify (plain text)
     * @return bool
     */
    public function verify_password($id, $password) {
        $this->db->where('id', $id);
        $query = $this->db->get('tb_user', 1);
        
        if ($query->num_rows() === 0) {
            return false;
        }
        
        $user = $query->row();
        return password_verify($password, $user->password);
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
     * Check if email exists (excluding current user)
     * @param string $email Email address
     * @param int $exclude_id User ID to exclude
     * @return bool
     */
    public function email_exists($email, $exclude_id) {
        $this->db->where('email', $email);
        $this->db->where('id !=', $exclude_id);
        $query = $this->db->get('tb_user', 1);
        
        return $query->num_rows() > 0;
    }
    
    /**
     * Get old photo filename for user
     * @param int $id User ID
     * @return string|null Photo filename or null if none
     */
    public function get_old_photo($id) {
        $this->db->select('foto_profil');
        $this->db->where('id', $id);
        $query = $this->db->get('tb_user', 1);
        
        if ($query->num_rows() === 0) {
            return null;
        }
        
        $user = $query->row();
        return $user->foto_profil;
    }
}
