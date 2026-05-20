<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_import extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function import_siswa($data_array, $id_kelas = null)
    {
        $this->db->trans_start();
        
        $success = 0;
        $failed = 0;
        $duplicate = 0;
        $details = [];
        
        foreach ($data_array as $row) {
            // Format: NIS, Nama, JK, Tempat Lahir, Tanggal Lahir, Alamat, Nama Ortu, No HP Ortu
            $nis = isset($row[0]) ? trim($row[0]) : '';
            $nama = isset($row[1]) ? trim($row[1]) : '';
            $jk = isset($row[2]) ? strtoupper(trim($row[2])) : 'L';
            $tempat_lahir = isset($row[3]) ? trim($row[3]) : '';
            $tanggal_lahir = isset($row[4]) ? $this->format_date($row[4]) : null;
            $alamat = isset($row[5]) ? trim($row[5]) : '';
            $nama_ortu = isset($row[6]) ? trim($row[6]) : '';
            $no_hp_orang_tua = isset($row[7]) ? trim($row[7]) : '';
            
            // Skip header row or empty rows
            if (empty($nis) || $nis === 'NIS') {
                continue;
            }
            
            // Check duplicate NIS
            $this->db->where('nis', $nis);
            if ($this->db->get('tb_siswa')->num_rows() > 0) {
                $duplicate++;
                $details[] = ['nis' => $nis, 'status' => 'duplicate', 'message' => 'NIS sudah ada'];
                continue;
            }
            
            // Create user account
            $username = $nis;
            $default_password = 'siswa123';
            $hashed_password = password_hash($default_password, PASSWORD_BCRYPT);
            
            $user_data = [
                'username' => $username,
                'password' => $hashed_password,
                'nama_lengkap' => $nama,
                'role' => 'siswa',
                'status' => 'aktif'
            ];
            
            $this->db->insert('tb_user', $user_data);
            $id_user = $this->db->insert_id();
            
            // Create siswa record
            $siswa_data = [
                'nis' => $nis,
                'id_user' => $id_user,
                'id_kelas' => $id_kelas,
                'nama' => $nama,
                'jenis_kelamin' => in_array($jk, ['L', 'P']) ? $jk : 'L',
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'alamat' => $alamat,
                'nama_ortu' => $nama_ortu,
                'no_hp_ortu' => $no_hp_orang_tua
            ];
            
            if ($this->db->insert('tb_siswa', $siswa_data)) {
                $success++;
                $details[] = ['nis' => $nis, 'status' => 'success', 'message' => 'Berhasil diimport'];
            } else {
                $failed++;
                $details[] = ['nis' => $nis, 'status' => 'failed', 'message' => 'Gagal insert data'];
                // Rollback user if siswa insert fails
                $this->db->where('id', $id_user);
                $this->db->delete('tb_user');
            }
        }
        
        $this->db->trans_complete();
        
        return [
            'success' => $success,
            'failed' => $failed,
            'duplicate' => $duplicate,
            'details' => $details,
            'transaction_status' => $this->db->trans_status()
        ];
    }

    public function import_guru($data_array)
    {
        $this->db->trans_start();
        
        $success = 0;
        $failed = 0;
        $duplicate = 0;
        $details = [];
        
        foreach ($data_array as $row) {
            // Format: NIP, Nama, JK, Alamat, No HP
            $nip = isset($row[0]) ? trim($row[0]) : '';
            $nama = isset($row[1]) ? trim($row[1]) : '';
            $jk = isset($row[2]) ? strtoupper(trim($row[2])) : 'L';
            $alamat = isset($row[3]) ? trim($row[3]) : '';
            $no_hp = isset($row[4]) ? trim($row[4]) : '';
            
            // Skip header row or empty rows
            if (empty($nip) || $nip === 'NIP') {
                continue;
            }
            
            // Check duplicate NIP
            $this->db->where('nip', $nip);
            if ($this->db->get('tb_guru')->num_rows() > 0) {
                $duplicate++;
                $details[] = ['nip' => $nip, 'status' => 'duplicate', 'message' => 'NIP sudah ada'];
                continue;
            }
            
            // Create user account
            $username = $nip;
            $default_password = 'guru123';
            $hashed_password = password_hash($default_password, PASSWORD_BCRYPT);
            
            $user_data = [
                'username' => $username,
                'password' => $hashed_password,
                'nama_lengkap' => $nama,
                'role' => 'guru',
                'status' => 'aktif'
            ];
            
            $this->db->insert('tb_user', $user_data);
            $id_user = $this->db->insert_id();
            
            // Create guru record
            $guru_data = [
                'nip' => $nip,
                'id_user' => $id_user,
                'nama' => $nama,
                'jenis_kelamin' => in_array($jk, ['L', 'P']) ? $jk : 'L',
                'alamat' => $alamat,
                'no_hp' => $no_hp
            ];
            
            if ($this->db->insert('tb_guru', $guru_data)) {
                $success++;
                $details[] = ['nip' => $nip, 'status' => 'success', 'message' => 'Berhasil diimport'];
            } else {
                $failed++;
                $details[] = ['nip' => $nip, 'status' => 'failed', 'message' => 'Gagal insert data'];
                // Rollback user if guru insert fails
                $this->db->where('id', $id_user);
                $this->db->delete('tb_user');
            }
        }
        
        $this->db->trans_complete();
        
        return [
            'success' => $success,
            'failed' => $failed,
            'duplicate' => $duplicate,
            'details' => $details,
            'transaction_status' => $this->db->trans_status()
        ];
    }

    private function format_date($date_string)
    {
        // Try to parse various date formats
        if (empty($date_string)) return null;
        
        // If already in Y-m-d format
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_string)) {
            return $date_string;
        }
        
        // Try DD/MM/YYYY
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $date_string, $matches)) {
            return sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
        }
        
        // Try DD-MM-YYYY
        if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $date_string, $matches)) {
            return sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
        }
        
        return null;
    }
}
