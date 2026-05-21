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
        $this->db->select('g.*, u.email, u.nama_lengkap as user_nama');
        $this->db->from($this->table . ' g');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        $this->db->where('g.id', $id);
        return $this->db->get()->row_array();
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

    /**
     * Cek apakah guru digunakan sebagai wali kelas di tabel kelas
     * @param int $id_guru ID guru
     * @return bool true jika guru digunakan sebagai wali kelas
     */
    public function is_guru_used_as_wali_kelas($id_guru)
    {
        $this->db->from('tb_kelas');
        $this->db->where('id_wali_kelas', $id_guru);
        return $this->db->count_all_results() > 0;
    }

    /**
     * Cek apakah guru digunakan di tabel jadwal
     * @param int $id_guru ID guru
     * @return bool true jika guru digunakan di jadwal
     */
    public function is_guru_used_in_jadwal($id_guru)
    {
        $this->db->from('tb_jadwal');
        $this->db->where('id_guru', $id_guru);
        return $this->db->count_all_results() > 0;
    }

    /**
     * Cek apakah guru bisa dihapus (tidak digunakan di tabel lain)
     * @param int $id_guru ID guru
     * @return array ['can_delete' => bool, 'reasons' => array]
     */
    public function can_guru_be_deleted($id_guru)
    {
        $reasons = [];
        
        if ($this->is_guru_used_as_wali_kelas($id_guru)) {
            $reasons[] = 'Guru ini sedang menjadi wali kelas di tabel Kelas';
        }
        
        if ($this->is_guru_used_in_jadwal($id_guru)) {
            $reasons[] = 'Guru ini memiliki jadwal mengajar di tabel Jadwal';
        }
        
        return [
            'can_delete' => empty($reasons),
            'reasons' => $reasons
        ];
    }

    /**
     * Get all active teachers for dropdown
     * @return array
     */
    public function get_active_teachers()
    {
        $this->db->select('g.id, u.nama_lengkap as nama_guru');
        $this->db->from($this->table . ' g');
        $this->db->join('tb_user u', 'u.id = g.id_user');
        $this->db->where('g.status_aktif', 1);
        return $this->db->get()->result();
    }
}
