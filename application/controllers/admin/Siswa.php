<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->role_required = ['admin'];
        $this->load->model('admin/M_siswa');
        $this->load->model('admin/M_kelas');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['judul'] = 'Manajemen Siswa';
        $data['tahun_ajaran'] = $this->tahun_ajaran_aktif;
        $this->load->view('templates/template', ['contents' => $this->load->view('admin/siswa', $data, TRUE)]);
    }

    public function ajax_list()
    {
        $id_tahun_ajaran = $this->tahun_ajaran_aktif['id'] ?? null;
        $list = $this->M_siswa->get_all_datatables($id_tahun_ajaran);
        
        $output = [
            'draw' => 0,
            'recordsTotal' => count($list),
            'recordsFiltered' => count($list),
            'data' => []
        ];
        
        foreach ($list as $row) {
            $jk_badge = $row['jenis_kelamin'] == 'L' ? '<span class="badge bg-info">L</span>' : '<span class="badge bg-pink">P</span>';
            $status_badge = $row['user_status'] == 'aktif' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>';
            
            $output['data'][] = [
                $row['nis'],
                $row['nama'],
                $jk_badge,
                $row['nama_kelas'] ?? '-',
                $row['username'],
                $status_badge,
                '<button class="btn btn-sm btn-warning edit-btn" data-id="' . encrypt_id($row['id']) . '"><i class="fas fa-edit"></i></button>
                 <button class="btn btn-sm btn-danger delete-btn" data-id="' . encrypt_id($row['id']) . '"><i class="fas fa-trash"></i></button>'
            ];
        }
        
        echo json_encode($output);
    }

    public function ajax_add()
    {
        $this->form_validation->set_rules('nis', 'NIS', 'required|trim|is_unique[tb_siswa.nis]');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required|in_list[L,P]');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
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
            'nama' => $this->input->post('nama'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'alamat' => $this->input->post('alamat'),
            'nama_orang_tua' => $this->input->post('nama_orang_tua'),
            'no_hp_orang_tua' => $this->input->post('no_hp_orang_tua')
        ];
        
        if ($this->M_siswa->insert($siswa_data)) {
            log_aktivitas('INSERT', 'tb_siswa', $this->db->insert_id(), 'Tambah siswa ' . $siswa_data['nama']);
            echo json_encode(['status' => true, 'message' => 'Siswa berhasil ditambahkan']);
        } else {
            // Rollback user
            $this->db->where('id', $id_user);
            $this->db->delete('tb_user');
            echo json_encode(['status' => false, 'message' => 'Gagal menambahkan siswa']);
        }
    }

    public function ajax_edit($id)
    {
        $id_decrypted = decrypt_id($id);
        $data = $this->M_siswa->get_by_id($id_decrypted);
        
        if ($data) {
            echo json_encode(['status' => true, 'data' => $data]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
    }

    public function ajax_update()
    {
        $id = decrypt_id($this->input->post('id'));
        $this->form_validation->set_rules('nis', 'NIS', 'required|trim');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }
        
        $siswa_data = [
            'nis' => $this->input->post('nis'),
            'id_kelas' => $this->input->post('id_kelas') ?: null,
            'nama' => $this->input->post('nama'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'alamat' => $this->input->post('alamat'),
            'nama_orang_tua' => $this->input->post('nama_orang_tua'),
            'no_hp_orang_tua' => $this->input->post('no_hp_orang_tua')
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
            log_aktivitas('UPDATE', 'tb_siswa', $id, 'Update siswa ' . $siswa_data['nama']);
            echo json_encode(['status' => true, 'message' => 'Siswa berhasil diperbarui']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal memperbarui siswa']);
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
            echo json_encode(['status' => true, 'message' => 'Siswa berhasil dihapus']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus siswa']);
        }
    }

    public function get_kelas_select()
    {
        $id_tahun_ajaran = $this->tahun_ajaran_aktif['id'] ?? null;
        $kelas = $this->M_siswa->get_kelas_for_select($id_tahun_ajaran);
        echo json_encode($kelas);
    }
}
