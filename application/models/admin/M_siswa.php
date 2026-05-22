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
        $this->db->select('s.id, s.nis, COALESCE(u.nama_lengkap, u.username) as nama_siswa, s.jenis_kelamin');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_user u', 'u.id = s.id_user', 'left');
        $this->db->order_by('nama_siswa', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Create siswa dengan user account
     * @param string $nis
     * @param string $nama
     * @param string $jk
     * @param string $ttl
     * @param string $alamat
     * @param string $nama_ortu
     * @param string $no_hp_ortu
     * @return bool|int ID siswa jika berhasil, false jika gagal
     */
    public function create_with_user($nis, $nama, $jk, $ttl, $alamat, $nama_ortu, $no_hp_ortu)
    {
        // Generate username dari NIS
        $username = $nis;
        
        // Cek apakah user sudah ada
        $check_user = $this->db->where('username', $username)->get('tb_user')->row();
        
        if ($check_user) {
            log_message('error', 'User dengan username ' . $username . ' sudah ada');
            return false;
        }
        
        // Password default: nis (tanpa spasi)
        $password_default = preg_replace('/\s+/', '', $nis);
        
        // Insert ke tb_user terlebih dahulu
        $user_data = array(
            'username' => $username,
            'password' => password_hash($password_default, PASSWORD_DEFAULT),
            'role' => 'siswa',
            'nama_lengkap' => $nama,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->insert('tb_user', $user_data);
        $id_user = $this->db->insert_id();
        
        if (!$id_user) {
            log_message('error', 'Gagal insert user untuk siswa');
            return false;
        }
        
        // Parse TTL menjadi tempat_lahir dan tanggal_lahir
        $tempat_lahir = '';
        $tanggal_lahir = null;
        
        // Format: "Tempat, DD Bulan YYYY" atau "Tempat, DD-MM-YYYY"
        if (strpos($ttl, ',') !== false) {
            $parts = explode(',', $ttl, 2);
            $tempat_lahir = trim($parts[0]);
            $tgl_str = trim($parts[1]);
            
            // Coba parse berbagai format tanggal
            $tanggal_lahir = $this->parse_date($tgl_str);
        } else {
            $tempat_lahir = trim($ttl);
        }
        
        // Insert ke tb_siswa
        $siswa_data = array(
            'id_user' => $id_user,
            'nis' => $nis,
            'nama_lengkap' => $nama,
            'jenis_kelamin' => in_array($jk, array('L', 'P')) ? $jk : 'L',
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'nama_ortu' => $nama_ortu,
            'no_hp_ortu' => $no_hp_ortu,
            'status_aktif' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->insert('tb_siswa', $siswa_data);
        $id_siswa = $this->db->insert_id();
        
        if (!$id_siswa) {
            // Rollback user jika insert siswa gagal
            $this->db->where('id', $id_user)->delete('tb_user');
            log_message('error', 'Gagal insert siswa');
            return false;
        }
        
        return $id_siswa;
    }
    
    /**
     * Parse string tanggal ke format MySQL DATE
     * @param string $date_str
     * @return string|null Date dalam format Y-m-d atau null jika gagal
     */
    private function parse_date($date_str)
    {
        $bulan_indo = array(
            'januari' => '01', 'februari' => '02', 'maret' => '03', 'april' => '04',
            'mei' => '05', 'juni' => '06', 'juli' => '07', 'agustus' => '08',
            'september' => '09', 'oktober' => '10', 'november' => '11', 'desember' => '12'
        );
        
        $date_str = strtolower(trim($date_str));
        
        // Format: DD Bulan YYYY (contoh: 15 Januari 2013)
        foreach ($bulan_indo as $bulan_nama => $bulan_num) {
            if (strpos($date_str, $bulan_nama) !== false) {
                $pattern = '/(\d{1,2})\s+' . $bulan_nama . '\s+(\d{4})/i';
                if (preg_match($pattern, $date_str, $matches)) {
                    $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                    $year = $matches[2];
                    return $year . '-' . $bulan_num . '-' . $day;
                }
            }
        }
        
        // Format: DD-MM-YYYY
        if (preg_match('/(\d{1,2})-(\d{1,2})-(\d{4})/', $date_str, $matches)) {
            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $year = $matches[3];
            return $year . '-' . $month . '-' . $day;
        }
        
        // Format: YYYY-MM-DD
        if (preg_match('/(\d{4})-(\d{1,2})-(\d{1,2})/', $date_str, $matches)) {
            return $matches[1] . '-' . str_pad($matches[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($matches[3], 2, '0', STR_PAD_LEFT);
        }
        
        // Default: coba dengan strtotime
        $timestamp = strtotime($date_str);
        if ($timestamp) {
            return date('Y-m-d', $timestamp);
        }
        
        return null;
    }
}