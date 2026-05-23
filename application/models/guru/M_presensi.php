<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model: Guru - Presensi
 * Mengelola operasi database untuk presensi siswa oleh guru
 */
class M_presensi extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get jadwal mengajar guru untuk hari ini
     * @param int $id_user ID user (dari session)
     * @param int|null $id_tahun_ajaran ID tahun ajaran
     * @return array Jadwal list untuk hari ini
     */
    public function get_jadwal_hari_ini($id_user, $id_tahun_ajaran = null)
    {
        // Mapping hari dari format PHP (Bahasa Inggris) ke Bahasa Indonesia
        $hari_indo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $hari_ini = $hari_indo[date('l')];
        
        // Dapatkan id_guru dari tb_guru berdasarkan id_user
        $this->db->where('id_user', $id_user);
        $guru = $this->db->get('tb_guru')->row();
        
        if (!$guru) {
            log_message('error', 'M_presensi::get_jadwal_hari_ini - Guru tidak ditemukan untuk user_id: ' . $id_user);
            return [];
        }
        
        $id_guru = $guru->id;
        
        $this->db->select('j.*, k.nama_kelas, m.nama_mapel, g.status_aktif as guru_status');
        $this->db->from('tb_jadwal j');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas', 'left');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel', 'left');
        $this->db->join('tb_guru g', 'g.id = j.id_guru', 'left');
        $this->db->where('j.id_guru', $id_guru);
        $this->db->where('j.hari', $hari_ini);
        
        // Hanya tampilkan jadwal yang aktif
        $this->db->where('j.status_aktif', 1);
        
        // Filter tahun ajaran jika diberikan
        if ($id_tahun_ajaran !== null && $id_tahun_ajaran > 0) {
            $this->db->where('j.id_tahun_ajaran', $id_tahun_ajaran);
        }
        
        $this->db->order_by('j.jam_mulai', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Get detail jadwal dengan informasi lengkap
     * @param int $id_jadwal ID jadwal
     * @return array|null Data jadwal
     */
    public function get_jadwal_detail($id_jadwal)
    {
        $this->db->select('j.*, k.nama_kelas, k.id as id_kelas, m.nama_mapel, 
                          g.id as id_guru, g.nip, g.nama_lengkap as nama_guru,
                          u.id as id_guru_user, u.username as username_guru');
        $this->db->from('tb_jadwal j');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas', 'left');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel', 'left');
        $this->db->join('tb_guru g', 'g.id = j.id_guru', 'left');
        $this->db->join('tb_user u', 'u.id = g.id_user', 'left');
        $this->db->where('j.id', $id_jadwal);
        
        return $this->db->get()->row_array();
    }

    /**
     * Get daftar siswa berdasarkan kelas
     * @param int $id_kelas ID kelas
     * @return array Daftar siswa
     */
    public function get_siswa_by_kelas($id_kelas)
    {
        $this->db->select('s.id, s.nis, u.nama_lengkap, s.jenis_kelamin, 
                          s.tempat_lahir, s.tanggal_lahir, s.alamat, 
                          s.nama_ortu, s.no_hp_ortu');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_user u', 'u.id = s.id_user', 'left');
        $this->db->where('s.id_kelas', $id_kelas);
        $this->db->where('s.status_aktif', 1);
        $this->db->order_by('u.nama_lengkap', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Cek apakah sudah ada presensi untuk jadwal dan tanggal tertentu
     * @param int $id_jadwal ID jadwal
     * @param string $tanggal Tanggal (Y-m-d)
     * @return bool True jika sudah ada, false jika belum
     */
    public function check_presensi_exists($id_jadwal, $tanggal)
    {
        $this->db->where('id_jadwal', $id_jadwal); 
		$this->db->where('tanggal', $tanggal);
        return $this->db->count_all_results('tb_presensi') > 0;
    }
    
    /**
     * Get presensi by jadwal and tanggal
     * @param int $id_jadwal ID jadwal
     * @param string $tanggal Tanggal (Y-m-d)
     * @return array|null Data presensi jika ada
     */
    public function get_presensi_by_jadwal_tanggal($id_jadwal, $tanggal)
    {
        $this->db->where('id_jadwal', $id_jadwal);
        $this->db->where('tanggal', $tanggal);
        return $this->db->get('tb_presensi')->row_array();
    }
 
    /**
     * Get detail presensi dengan informasi lengkap
     * @param int $id_presensi ID presensi
     * @return array|null Data presensi
     */
    public function get_presensi_detail($id_presensi)
    {
        $this->db->select('p.*, j.hari, j.jam_mulai, j.jam_selesai, j.ruangan,
                          k.nama_kelas, k.id as id_kelas, m.nama_mapel,
                          g.id as id_guru, g.nip, g.nama_lengkap as nama_guru,
                          u.id as id_guru_user, u.username as username_guru');
        $this->db->from('tb_presensi p');
        $this->db->join('tb_jadwal j', 'j.id = p.id_jadwal', 'left');
        $this->db->join('tb_kelas k', 'k.id = j.id_kelas', 'left');
        $this->db->join('tb_mata_pelajaran m', 'm.id = j.id_mapel', 'left');
        $this->db->join('tb_guru g', 'g.id = p.id_guru', 'left');
        $this->db->join('tb_user u', 'u.id = g.id_user', 'left');
        $this->db->where('p.id', $id_presensi);
        
        return $this->db->get()->row_array();
    }

    /**
     * Get data siswa yang sudah di-presensi untuk suatu presensi
     * @param int $id_presensi ID presensi
     * @return array Daftar siswa dengan status presensi
     */
    public function get_siswa_presensi($id_presensi)
    {
        $this->db->select('ps.id as id_presensi_siswa, ps.id_siswa, ps.status, ps.keterangan,
                          u.nama_lengkap, s.nis, s.jenis_kelamin');
        $this->db->from('tb_presensi_siswa ps');
        $this->db->join('tb_siswa s', 's.id = ps.id_siswa', 'left');
        $this->db->join('tb_user u', 'u.id = s.id_user', 'left');
        $this->db->where('ps.id_presensi', $id_presensi);
        $this->db->order_by('u.nama_lengkap', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Simpan presensi (create atau update)
     * @param array $data Data presensi lengkap
     * @param int|null $id_jadwal ID jadwal (untuk create)
     * @param int|null $id_presensi ID presensi (untuk update)
     * @return array ['success' => bool, 'message' => string, 'id_presensi' => int|null]
     */
    public function simpan_presensi($data, $id_jadwal = null, $id_presensi = null)
    {
        log_message('debug', 'M_presensi::simpan_presensi - Start with id_jadwal: ' . $id_jadwal);
        log_message('debug', 'M_presensi::simpan_presensi - Data: ' . print_r($data, true));
        
        $this->db->trans_start();
        
        $created_at = date('Y-m-d H:i:s');
        $tanggal = $data['tanggal'];
        $materi_pelajaran = $data['materi_pelajaran'];
        $id_guru = $data['id_guru'];
        $siswa_data = isset($data['siswa']) ? $data['siswa'] : [];
        
        log_message('debug', "M_presensi::simpan_presensi - Processing {$tanggal}, guru: {$id_guru}, siswa count: " . count($siswa_data));
        
        // Cek apakah sudah ada presensi untuk jadwal dan tanggal ini
        $existing_presensi = $this->get_presensi_by_jadwal_tanggal($id_jadwal, $tanggal);
        
        if ($existing_presensi) {
            log_message('debug', 'M_presensi::simpan_presensi - Existing presensi found: ' . $existing_presensi['id']);
        } else {
            log_message('debug', 'M_presensi::simpan_presensi - No existing presensi, will create new');
        }
        
        $final_id_presensi = null;
        
        if ($existing_presensi) {
            // UPDATE existing presensi
            $final_id_presensi = $existing_presensi['id'];
            
            // Update materi pelajaran
            $update_data = [
                'materi_pelajaran' => $materi_pelajaran,
                'id_guru' => $id_guru
            ];
            
            $this->db->where('id', $final_id_presensi);
            $this->db->update('tb_presensi', $update_data);
            log_message('debug', 'M_presensi::simpan_presensi - Updated tb_presensi ID: ' . $final_id_presensi);
            
            // Delete existing siswa presensi
            $this->db->where('id_presensi', $final_id_presensi);
            $this->db->delete('tb_presensi_siswa');
            log_message('debug', 'M_presensi::simpan_presensi - Deleted existing siswa presensi');
            
        } else {
            // CREATE new presensi
            $presensi_data = [
                'id_jadwal' => $id_jadwal,
                'id_guru' => $id_guru,
                'materi_pelajaran' => $materi_pelajaran,
                'tanggal' => $tanggal,
                'waktu_input' => $created_at,
                'created_at' => $created_at
            ];
            
            $this->db->insert('tb_presensi', $presensi_data);
            $final_id_presensi = $this->db->insert_id();
            log_message('debug', 'M_presensi::simpan_presensi - Inserted new tb_presensi ID: ' . $final_id_presensi);
        }
        
        // Insert data siswa presensi
        if ($final_id_presensi && is_array($siswa_data) && count($siswa_data) > 0) {
            $insert_count = 0;
            foreach ($siswa_data as $id_siswa => $data_siswa) {
                $status = isset($data_siswa['status']) ? $data_siswa['status'] : 'Hadir';
                $keterangan = isset($data_siswa['keterangan']) ? htmlspecialchars(trim($data_siswa['keterangan'])) : null;
                
                $presensi_siswa_data = [
                    'id_presensi' => $final_id_presensi,
                    'id_siswa' => $id_siswa,
                    'tanggal' => $tanggal,
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'created_at' => $created_at
                ];
                
                $this->db->insert('tb_presensi_siswa', $presensi_siswa_data);
                $insert_count++;
            }
            log_message('debug', "M_presensi::simpan_presensi - Inserted {$insert_count} siswa presensi records");
        } else {
            log_message('warning', 'M_presensi::simpan_presensi - No siswa data to insert or final_id_presensi is null');
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'M_presensi::simpan_presensi - Transaksi database gagal');
            return [
                'success' => false,
                'message' => 'Gagal menyimpan presensi ke database!',
                'id_presensi' => null
            ];
        }
        
        log_message('info', 'M_presensi::simpan_presensi - Success! Final ID: ' . $final_id_presensi);
        
        return [
            'success' => true,
            'message' => $existing_presensi ? 'Presensi berhasil diperbarui!' : 'Presensi berhasil disimpan!',
            'id_presensi' => $final_id_presensi,
            'is_update' => (bool)$existing_presensi
        ];
    }

}