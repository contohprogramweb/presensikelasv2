<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_guru extends CI_Model {

    private $table = 'tb_guru';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_datatables()
    {
        $this->db->select('g.*, u.username, u.nama_lengkap as user_nama, u.status as user_status');
        $this->db->from($this->table . ' g');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        return $this->db->get()->result_array();
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

    public function check_nip_exists($nip, $exclude_id = null)
    {
        $this->db->where('nip', $nip);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->get($this->table)->num_rows() > 0;
    }
}
