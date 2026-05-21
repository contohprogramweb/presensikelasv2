<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->role_required = ['admin'];
        $this->load->model('admin/M_siswa');
        $this->load->model('admin/M_kelas');
        $this->load->model('M_dashboard');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = array_merge($this->data, ['judul' => 'Manajemen Siswa', 'content' => 'admin/siswa']);
        $this->load->view('templates/template', $data);
    }

    public function ajax_list()
    {
        $id_tahun_ajaran = $this->tahun_ajaran_aktif->id ?? null;
        $list = $this->M_siswa->get_all_datatables($id_tahun_ajaran);
        
        // FIX #1: draw harus dari POST['draw'] (integer), bukan hardcode 0
        $draw = isset($_POST['draw']) ? (int)$_POST['draw'] : 1;

        $output = [
            'draw'            => $draw,
            'recordsTotal'    => count($list),
            'recordsFiltered' => count($list),
            'data'            => [],
            'csrf_hash'       => $this->security->get_csrf_hash()
        ];
        
        foreach ($list as $index => $row) {
            $jk_badge = $row['jenis_kelamin'] == 'L' ? '<span class="badge bg-info">L</span>' : '<span class="badge bg-danger">P</span>';
            $status_badge = $row['user_status'] == 'aktif' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>';
            
            // Check if siswa is in riwayat kelas
            $is_in_riwayat = $this->M_siswa->is_siswa_in_riwayat($row['id']);
             
            
            if ($is_in_riwayat) {
               $reason_text = 'Siswa tidak dapat dihapus karena masih ada di riwayat kelas';
                $delete_button = '<button class="btn btn-sm btn-secondary" disabled title="' . htmlspecialchars($reason_text) . '"><i class="fas fa-trash"></i></button>';
            } else {
                // Guru tidak bisa dihapus, buat tooltip dengan alasan
                 $delete_button = '<button class="btn btn-sm btn-danger delete-btn" data-id="' . encrypt_id($row['id']) . '"><i class="fas fa-trash"></i></button>';
				 
				 
            }

            
            $output['data'][] = [
                ($index + 1),
                $row['nis'],
                $row['nama_lengkap'],
                $jk_badge,
                $row['nama_kelas'] ?? '-',
                $row['username'],
                $status_badge,
                '<button class="btn btn-sm btn-warning edit-btn" data-id="' . encrypt_id($row['id']) . '"><i class="fas fa-edit"></i></button>
                 ' . $delete_button
            ];
        }
        
        // FIX #2: set Content-Type JSON agar tidak ada HTML output sebelum JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    public function ajax_add()
    {
        $this->form_validation->set_rules('nis', 'NIS', 'required|trim|is_unique[tb_siswa.nis]');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required|in_list[L,P]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => validation_errors(), 'csrf_hash' => $this->security->get_csrf_hash()]));
            return;
        }
        
        $username = $this->input->post('nis');
        $default_password = 'siswa123';
        $hashed_password = password_hash($default_password, PASSWORD_BCRYPT);
        
        // Create user first
        $user_data = [
            'username' => $username,
            'password' => $hashed_password,
            'nama_lengkap' => $this->input->post('nama'),
            'email' => $this->input->post('email'),
            'no_hp' => $this->input->post('no_hp'),
            'role' => 'siswa',
            'status' => 'aktif'
        ];
        
        $this->db->insert('tb_user', $user_data);
        $id_user = $this->db->insert_id();
        
        // Create siswa
        $siswa_data = [
            'nis' => $this->input->post('nis'),
            'id_user' => $id_user,
            'id_kelas' => $this->input->post('id_kelas') ?: null,
			'nama_lengkap' => $this->input->post('nama'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'alamat' => $this->input->post('alamat'),
            'nama_ortu' => $this->input->post('nama_ortu'),
            'no_hp_ortu' => $this->input->post('no_hp_orang_tua'),
			'created_at' => date("Y-m-d H:i:s")
        ];
        
        if ($this->M_siswa->insert($siswa_data)) {
            log_aktivitas('INSERT', 'tb_siswa', $this->db->insert_id(), 'Tambah siswa ' . $user_data['nama_lengkap']);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => true, 'message' => 'Siswa berhasil ditambahkan', 'csrf_hash' => $this->security->get_csrf_hash()]));
        } else {
            // Rollback user
            $this->db->where('id', $id_user);
            $this->db->delete('tb_user');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Gagal menambahkan siswa', 'csrf_hash' => $this->security->get_csrf_hash()]));
        }
    }

    public function ajax_edit($id)
    {
        $id_decrypted = decrypt_id($id);
        $data = $this->M_siswa->get_by_id($id_decrypted);
        
        if ($data) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => true, 'data' => $data]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Data tidak ditemukan']));
        }
    }

    public function ajax_update()
    {
        $id = decrypt_id($this->input->post('id'));
        $this->form_validation->set_rules('nis', 'NIS', 'required|trim');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => validation_errors(), 'csrf_hash' => $this->security->get_csrf_hash()]));
            return;
        }
        
        $siswa_data = [
            'nis' => $this->input->post('nis'),
            'id_kelas' => $this->input->post('id_kelas') ?: null,
			'nama_lengkap' => $this->input->post('nama'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'alamat' => $this->input->post('alamat'),
            'nama_ortu' => $this->input->post('nama_ortu'),
            'no_hp_ortu' => $this->input->post('no_hp_orang_tua'),
			'updated_at' => date("Y-m-d H:i:s")
        ];
        
        // Update user info
        $user_data = [
            'nama_lengkap' => $this->input->post('nama'),
            'email' => $this->input->post('email'),
            'no_hp' => $this->input->post('no_hp')
        ];
        
        $siswa = $this->M_siswa->get_by_id($id);
        
        $this->db->trans_start();
        $this->M_siswa->update($id, $siswa_data);
        $this->db->where('id', $siswa['id_user']);
        $this->db->update('tb_user', $user_data);
        $this->db->trans_complete();
        
        if ($this->db->trans_status()) {
            log_aktivitas('UPDATE', 'tb_siswa', $id, 'Update siswa ' . $user_data['nama_lengkap']);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => true, 'message' => 'Siswa berhasil diperbarui', 'csrf_hash' => $this->security->get_csrf_hash()]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Gagal memperbarui siswa', 'csrf_hash' => $this->security->get_csrf_hash()]));
        }
    }

    public function ajax_delete($id)
    {
        $id_decrypted = decrypt_id($id);
        $siswa = $this->M_siswa->get_by_id($id_decrypted);
        
        $this->db->trans_start();
        $this->M_siswa->delete($id_decrypted);
        $this->db->where('id', $siswa['id_user']);
        $this->db->delete('tb_user');
        $this->db->trans_complete();
        
        if ($this->db->trans_status()) {
            log_aktivitas('DELETE', 'tb_siswa', $id_decrypted, 'Hapus siswa');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => true, 
                    'message' => 'Siswa berhasil dihapus',
                    'csrf_hash' => $this->security->get_csrf_hash()
                ]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false, 
                    'message' => 'Gagal menghapus siswa',
                    'csrf_hash' => $this->security->get_csrf_hash()
                ]));
        }
    }

    public function get_kelas_select()
    {
        // $this->tahun_ajaran_aktif is an object from MY_Controller (returns row())
        $id_tahun_ajaran = isset($this->tahun_ajaran_aktif->id) ? $this->tahun_ajaran_aktif->id : null;
        
        // Fallback: if no active year, get the latest year
        if (!$id_tahun_ajaran) {
            $tahun_terbaru = $this->M_dashboard->get_tahun_ajaran_terbaru();
            if ($tahun_terbaru) {
                $id_tahun_ajaran = $tahun_terbaru->id;
            }
        }
        
        $kelas = $this->M_siswa->get_kelas_for_select($id_tahun_ajaran);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($kelas));
    }
}
