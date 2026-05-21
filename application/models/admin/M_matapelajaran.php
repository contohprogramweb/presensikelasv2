<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_matapelajaran extends CI_Model {

    private $table = 'tb_mata_pelajaran';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Get all active subjects for dropdown
     * @return array
     */
    public function get_active_subjects()
    {
        $this->db->select('id, nama_mapel');
        $this->db->from($this->table);
        $this->db->where('status_aktif', 1);
        return $this->db->get()->result();
    }
}