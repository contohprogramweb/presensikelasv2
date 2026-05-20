<?php
/**
 * Model untuk log aktivitas
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class M_logaktivitas extends CI_Model {
    
    /**
     * Get semua log aktivitas dengan pagination
     * @param int $limit Limit records
     * @param int $offset Offset
     * @return array Result array
     */
    public function get_all($limit = 10, $offset = 0) {
        $this->db->select('tb_log_aktivitas.*, tb_user.username, tb_user.nama_lengkap');
        $this->db->from('tb_log_aktivitas');
        $this->db->join('tb_user', 'tb_user.id = tb_log_aktivitas.id_user', 'left');
        $this->db->order_by('tb_log_aktivitas.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }
    
    /**
     * Count total log aktivitas
     * @return int Total count
     */
    public function count_all() {
        return $this->db->count_all_results('tb_log_aktivitas');
    }
    
    /**
     * Get log by user ID
     * @param int $user_id User ID
     * @param int $limit Limit records
     * @param int $offset Offset
     * @return array Result array
     */
    public function get_by_user($user_id, $limit = 10, $offset = 0) {
        $this->db->where('id_user', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get('tb_log_aktivitas')->result();
    }
    
    /**
     * Get log by date range
     * @param string $start_date Start date (Y-m-d)
     * @param string $end_date End date (Y-m-d)
     * @return array Result array
     */
    public function get_by_date_range($start_date, $end_date) {
        $this->db->where('DATE(created_at) >=', $start_date);
        $this->db->where('DATE(created_at) <=', $end_date);
        $this->db->order_by('created_at', 'DESC');
        
        return $this->db->get('tb_log_aktivitas')->result();
    }
    
    /**
     * Delete old logs (older than 90 days)
     * @return bool
     */
    public function cleanup_old_logs() {
        $cutoff_date = date('Y-m-d', strtotime('-90 days'));
        $this->db->where('created_at <', $cutoff_date);
        return $this->db->delete('tb_log_aktivitas');
    }
}
