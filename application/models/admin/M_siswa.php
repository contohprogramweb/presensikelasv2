<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_siswa extends CI_Model {

    private $table = 'tb_siswa';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_datatables($id_tahun_ajaran = null)
    {
        $this->db->select('s.*, k.nama_kelas, u.username, u.status as user_status');
        $this->db->from($this->table . ' s');
        $this->db->join('tb_user u', 'u.id = s.id_user');
        $this->db->join('tb_kelas k', 'k.id = s.id_kelas' . ($id_tahun_ajaran ? ' AND k.id_tahun_ajaran = ' . (int)$id_tahun_ajaran : ''), 'left');
        
        $result = $this->db->get()->result_array();
        
        // Add nama_lengkap from tb_user to each row for display
        foreach ($result as &$row) {
            if (isset($row['nama_lengkap']) && !empty($row['nama_lengkap'])) {
                $row['nama_display'] = $row['nama_lengkap'];
            } else {
                $row['nama_display'] = $row['username'];
            }
        }
        
        return $result;
    }

    public function get_by_id($id)
    {
        $this->db->select('s.*, u.email, u.no_hp, u.nama_lengkap');
        $this->db->from($this->table . ' s');
        $this->db->join('tb_user u', 'u.id = s.id_user');
        $this->db->where('s.id', $id);
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

    public function get_kelas_for_select($id_tahun_ajaran = null)
    {
        $this->db->select('id, nama_kelas');
        $this->db->from('tb_kelas');
        if ($id_tahun_ajaran) {
            $this->db->where('id_tahun_ajaran', $id_tahun_ajaran);
        }
        $this->db->order_by('nama_kelas', 'ASC');
        $query = $this->db->get();
        
        // Return empty array if query fails
        if (!$query) {
            return [];
        }
        
        return $query->result_array();
    }

    public function check_nis_exists($nis, $exclude_id = null)
    {
        $this->db->where('nis', $nis);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->get($this->table)->num_rows() > 0;
    }

    public function is_siswa_in_riwayat($id_siswa)
    {
        $this->db->where('id_siswa', $id_siswa);
        $query = $this->db->get('tb_riwayat_kelas');
        return $query->num_rows() > 0;
    }

    public function get_all_students()
    {
        $this->db->select('s.id, s.nis, COALESCE(u.nama_lengkap, s.nama_lengkap) as nama_siswa, s.jenis_kelamin');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_user u', 'u.id = s.id_user', 'left');
        $this->db->order_by('COALESCE(u.nama_lengkap, s.nama_lengkap)', 'ASC');
        
        return $this->db->get()->result();
    }
}
