<?php
/**
 * Model untuk manajemen tahun ajaran
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class M_tahunajaran extends CI_Model {
    
    /**
     * Get all tahun ajaran
     * @return array Result array
     */
    public function get_all() {
        $this->db->order_by('tahun_ajaran', 'DESC');
        $this->db->order_by('semester', 'DESC');
        return $this->db->get('tb_tahun_ajaran')->result();
    }
    
    /**
     * Get tahun ajaran by ID
     * @param int $id Tahun ajaran ID
     * @return object|null
     */
    public function get_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('tb_tahun_ajaran', 1);
        
        if ($query->num_rows() === 0) {
            return null;
        }
        
        return $query->row();
    }
    
    /**
     * Insert tahun ajaran
     * @param array $data Data to insert
     * @return int Insert ID
     */
    public function insert($data) {
        $this->db->insert('tb_tahun_ajaran', $data);
        return $this->db->insert_id();
    }
    
    /**
     * Update tahun ajaran
     * @param int $id Tahun ajaran ID
     * @param array $data Data to update
     * @return bool
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('tb_tahun_ajaran', $data);
    }
    
    /**
     * Delete tahun ajaran
     * @param int $id Tahun ajaran ID
     * @return bool
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('tb_tahun_ajaran');
    }
    
    /**
     * Set active tahun ajaran (set others to inactive)
     * @param int $id Tahun ajaran ID to set as active
     * @return bool
     */
    public function set_active($id) {
        $this->db->trans_start();
        
        // Set all to inactive
        $this->db->update('tb_tahun_ajaran', array('status_aktif' => 0));
        
        // Set selected to active
        $this->db->where('id', $id);
        $this->db->update('tb_tahun_ajaran', array('status_aktif' => 1));
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    /**
     * Check if tahun ajaran exists (same tahun and semester)
     * @param string $tahun_ajaran Tahun ajaran
     * @param string $semester Semester
     * @param int|null $exclude_id Exclude this ID
     * @return bool
     */
    public function exists($tahun_ajaran, $semester, $exclude_id = null) {
        $this->db->where('tahun_ajaran', $tahun_ajaran);
        $this->db->where('semester', $semester);
        
        if ($exclude_id !== null) {
            $this->db->where('id !=', $exclude_id);
        }
        
        $query = $this->db->get('tb_tahun_ajaran', 1);
        return $query->num_rows() > 0;
    }
}
