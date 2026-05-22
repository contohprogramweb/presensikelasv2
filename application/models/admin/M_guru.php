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

    /**
     * Create guru dengan user account
     * @param string $nip
     * @param string $nama
     * @param string $jk
     * @param string $no_hp
     * @param string $alamat
     * @return bool|int ID guru jika berhasil, false jika gagal
     */
    public function create_with_user($nip, $nama, $jk, $no_hp, $alamat)
    {
        // Generate username dari NIP
        $username = $nip;
        
        // Cek apakah user sudah ada
        $check_user = $this->db->where('username', $username)->get('tb_user')->row();
        
        if ($check_user) {
            log_message('error', 'User dengan username ' . $username . ' sudah ada');
            return false;
        }
        
        // Password default: nip (tanpa spasi)
        $password_default = preg_replace('/\s+/', '', $nip);
        
        // Insert ke tb_user terlebih dahulu
        $user_data = array(
            'username' => $username,
            'password' => password_hash($password_default, PASSWORD_DEFAULT),
            'role' => 'guru',
            'nama_lengkap' => $nama,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->insert('tb_user', $user_data);
        $id_user = $this->db->insert_id();
        
        if (!$id_user) {
            log_message('error', 'Gagal insert user untuk guru');
            return false;
        }
        
        // Insert ke tb_guru
        $guru_data = array(
            'id_user' => $id_user,
            'nip' => $nip,
            'nama_lengkap' => $nama,
            'jenis_kelamin' => in_array($jk, array('L', 'P')) ? $jk : 'L',
            'no_hp' => $no_hp,
            'alamat' => $alamat,
            'status_aktif' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->insert('tb_guru', $guru_data);
        $id_guru = $this->db->insert_id();
        
        if (!$id_guru) {
            // Rollback user jika insert guru gagal
            $this->db->where('id', $id_user)->delete('tb_user');
            log_message('error', 'Gagal insert guru');
            return false;
        }
        
        return $id_guru;
    }
}