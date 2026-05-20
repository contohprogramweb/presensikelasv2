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
        $this->db->select('k.*, u.nama_lengkap as wali_nama, t.tahun_ajaran, t.semester');
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

    /**
     * Cek apakah guru sudah menjadi wali kelas di tahun ajaran tertentu
     * @param int $id_guru ID guru
     * @param int $id_tahun_ajaran ID tahun ajaran
     * @param int|null $exclude_id ID kelas yang dikecualikan (untuk update)
     * @return bool true jika guru sudah menjadi wali kelas lain
     */
    public function is_guru_already_wali($id_guru, $id_tahun_ajaran, $exclude_id = null)
    {
        if (!$id_guru) {
            return false; // Tidak ada wali kelas, jadi tidak konflik
        }

        $this->db->from($this->table);
        $this->db->where('id_wali_kelas', $id_guru);
        $this->db->where('id_tahun_ajaran', $id_tahun_ajaran);
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }

        return $this->db->count_all_results() > 0;
    }
}
