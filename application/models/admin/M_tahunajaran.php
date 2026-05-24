<?php
/**
 * Model untuk manajemen tahun ajaran
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class M_tahunajaran extends CI_Model {
    
    private $table = 'tb_tahun_ajaran';
    private $column_order = ['id', 'tahun_ajaran', 'semester', 'status_aktif', 'tanggal_dibuat'];
    private $column_search = ['tahun_ajaran', 'semester'];
    private $order = ['id' => 'DESC'];
    
    /**
     * Get datatables data
     */
    public function get_datatables() {
        $this->_get_datatables_query();
        
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        
        $query = $this->db->get($this->table);
        return $query->result();
    }
    
    /**
     * Build datatables query
     */
    private function _get_datatables_query() {
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                
                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        
        if (isset($_POST['order'])) {
            $this->db->order_by(
                $this->column_order[$_POST['order']['0']['column']], 
                $_POST['order']['0']['dir']
            );
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    
    /**
     * Count all records
     */
    public function count_all() {
        return $this->db->count_all($this->table);
    }
    
    /**
     * Count filtered records
     */
    public function count_filtered() {
        $this->_get_datatables_query();
        return $this->db->get($this->table)->num_rows();
    }
    
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
    public function save($data) {
        $this->db->insert('tb_tahun_ajaran', $data);
        return $this->db->insert_id();
    }
    
    /**
     * Update tahun ajaran
     * @param int $id Tahun ajaran ID
     * @param array $data Data to update
     * @return bool
     */
    public function update($where, $data) {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }
    
    /**
     * Delete by ID
     */
    public function delete_by_id($id) {
        $this->db->delete($this->table, ['id' => $id]);
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
    
    /**
     * Get tahun ajaran aktif
     */
    public function get_aktif() {
        return $this->db->get_where($this->table, ['status_aktif' => 1])->row();
    }
    
    /**
     * Check if tahun ajaran is used in other tables
     * @param int $id Tahun ajaran ID
     * @return bool
     */
    public function is_used_in_other_tables($id) {
        // Check in tb_kelas
        $this->db->where('id_tahun_ajaran', $id);
        $count_kelas = $this->db->count_all_results('tb_kelas');
        
        if ($count_kelas > 0) {
            return true;
        }
        
        // Check in tb_jadwal
        $this->db->where('id_tahun_ajaran', $id);
        $count_jadwal = $this->db->count_all_results('tb_jadwal');
        
        if ($count_jadwal > 0) {
            return true;
        }
        
        return false;
    }
}
