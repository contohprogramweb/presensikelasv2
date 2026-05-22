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
        $this->db->where('DATE(tanggal)', $tanggal);
        return $this->db->count_all_results('tb_presensi') > 0;
    }

    /**
     * Simpan data presensi baru
     * @param array $data Data presensi
     * @return array Result ['success' => bool, 'message' => string, 'id_presensi' => int]
     */
    public function simpan_presensi($data)
    {
        // Log untuk debugging
        log_message('debug', 'M_presensi::simpan_presensi - Data received: ' . json_encode([
            'id_jadwal' => $data['id_jadwal'] ?? null,
            'id_guru' => $data['id_guru'] ?? null,
            'tanggal' => $data['tanggal'] ?? null,
            'materi_pelajaran' => substr($data['materi_pelajaran'] ?? '', 0, 50),
            'siswa_count' => is_array($data['siswa_data']) ? count($data['siswa_data']) : 0
        ]));
        
        // Validasi data
        if (empty($data['id_jadwal']) || empty($data['id_guru']) || empty($data['tanggal']) || empty($data['siswa_data'])) {
            log_message('error', 'M_presensi::simpan_presensi - Data presensi tidak lengkap');
            return [
                'success' => false,
                'message' => 'Data presensi tidak lengkap',
                'id_presensi' => null
            ];
        }

        // Mulai transaksi
        $this->db->trans_start();

        try {
            // Insert ke tb_presensi
            $presensi_data = [
                'id_jadwal' => $data['id_jadwal'],
                'id_guru' => $data['id_guru'],
                'materi_pelajaran' => $data['materi_pelajaran'] ?? null,
                'tanggal' => $data['tanggal'],
                'waktu_input' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            log_message('debug', 'M_presensi::simpan_presensi - Inserting presensi data: ' . json_encode($presensi_data));
            
            $this->db->insert('tb_presensi', $presensi_data);
            $id_presensi = $this->db->insert_id();

            if (!$id_presensi) {
                $error = $this->db->error();
                log_message('error', 'M_presensi::simpan_presensi - Gagal insert header. DB Error: ' . json_encode($error));
                throw new Exception('Gagal menyimpan data header presensi: ' . $error['message']);
            }
            
            log_message('debug', 'M_presensi::simpan_presensi - Header inserted with ID: ' . $id_presensi);

            // Prepare data untuk insert batch ke tb_presensi_siswa
            $siswa_presensi_data = [];
            foreach ($data['siswa_data'] as $id_siswa => $status_info) {
                $status = $status_info['status'] ?? 'Hadir';
                $keterangan = $status_info['keterangan'] ?? null;

                $siswa_presensi_data[] = [
                    'id_presensi' => $id_presensi,
                    'id_siswa' => $id_siswa,
                    'tanggal' => $data['tanggal'],
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }

            log_message('debug', 'M_presensi::simpan_presensi - Inserting ' . count($siswa_presensi_data) . ' siswa records');

            // Insert batch ke tb_presensi_siswa
            if (!empty($siswa_presensi_data)) {
                $result = $this->db->insert_batch('tb_presensi_siswa', $siswa_presensi_data);
                log_message('debug', 'M_presensi::simpan_presensi - Batch insert result: ' . ($result ? 'success' : 'failed'));
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $error = $this->db->error();
                log_message('error', 'M_presensi::simpan_presensi - Transaction failed. DB Error: ' . json_encode($error));
                return [
                    'success' => false,
                    'message' => 'Gagal menyimpan data presensi. Silakan coba lagi. Error: ' . $error['message'],
                    'id_presensi' => null
                ];
            }

            log_message('debug', 'M_presensi::simpan_presensi - Success! ID: ' . $id_presensi);
            
            return [
                'success' => true,
                'message' => 'Data presensi berhasil disimpan',
                'id_presensi' => $id_presensi
            ];

        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'M_presensi::simpan_presensi - Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
                'id_presensi' => null
            ];
        }
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
     * Update data presensi yang sudah ada
     * @param array $data Data presensi yang akan diupdate
     * @return array Result ['success' => bool, 'message' => string]
     */
    public function update_presensi($data)
    {
        // Validasi data
        if (empty($data['id_presensi']) || empty($data['siswa_data'])) {
            return [
                'success' => false,
                'message' => 'Data presensi tidak lengkap'
            ];
        }

        // Mulai transaksi
        $this->db->trans_start();

        try {
            // Update materi pelajaran di tb_presensi
            $update_data = [
                'materi_pelajaran' => $data['materi_pelajaran'] ?? null,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->where('id', $data['id_presensi']);
            $this->db->update('tb_presensi', $update_data);

            // Hapus data lama di tb_presensi_siswa untuk presensi ini
            $this->db->where('id_presensi', $data['id_presensi']);
            $this->db->delete('tb_presensi_siswa');

            // Insert data baru ke tb_presensi_siswa
            $siswa_presensi_data = [];
            foreach ($data['siswa_data'] as $id_siswa => $status_info) {
                $status = $status_info['status'] ?? 'Hadir';
                $keterangan = $status_info['keterangan'] ?? null;

                $siswa_presensi_data[] = [
                    'id_presensi' => $data['id_presensi'],
                    'id_siswa' => $id_siswa,
                    'tanggal' => date('Y-m-d'), // Akan diambil dari data presensi jika diperlukan
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }

            // Insert batch ke tb_presensi_siswa
            if (!empty($siswa_presensi_data)) {
                $this->db->insert_batch('tb_presensi_siswa', $siswa_presensi_data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui data presensi. Silakan coba lagi.'
                ];
            }

            return [
                'success' => true,
                'message' => 'Data presensi berhasil diperbarui'
            ];

        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'M_presensi::update_presensi - Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
            ];
        }
    }
}
