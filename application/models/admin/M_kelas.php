<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kelas extends CI_Model {

    private $table = 'tb_kelas';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all($id_tahun_ajaran = null)
    {
        $this->db->select('k.*, u.nama_lengkap as wali_nama, t.tahun, t.semester');
        $this->db->from($this->table . ' k');
        $this->db->join('tb_guru g', 'g.id = k.id_wali_kelas', 'left');
        $this->db->join('tb_user u', 'u.id = g.id_user', 'left');
        $this->db->join('tb_tahun_ajaran t', 't.id = k.id_tahun_ajaran');
        if ($id_tahun_ajaran) {
            $this->db->where('k.id_tahun_ajaran', $id_tahun_ajaran);
        }
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

    public function get_guru_for_select()
    {
        $this->db->select('g.id, u.nama_lengkap');
        $this->db->from('tb_guru g');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        $this->db->where('u.status', 'aktif');
        return $this->db->get()->result_array();
    }
}
