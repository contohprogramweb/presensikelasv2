    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Profile Box -->
        <div class="profile-box">
            <?php 
            $foto_profil = isset($user_data['foto_profil']) && !empty($user_data['foto_profil']) 
                ? base_url('assets/uploads/profil/' . $user_data['foto_profil'])
                : base_url('assets/img/default.png');
            ?>
            <img src="<?= $foto_profil; ?>" alt="Profile" id="sidebarProfileImg">
            <h6 id="sidebarProfileName"><?= html_escape(isset($user_data['nama_lengkap']) ? $user_data['nama_lengkap'] : ''); ?></h6>
            <?= badge_role(isset($user_data['role']) ? $user_data['role'] : ''); ?>
        </div>
        
        <!-- Navigation -->
        <nav class="nav flex-column mt-3">
            <?php $role = isset($user_data['role']) ? $user_data['role'] : ''; ?>
            
            <!-- Dashboard (All Roles) -->
            <a class="nav-link <?= ($this->uri->segment(1) == 'dashboard') ? 'active' : ''; ?>" href="<?= site_url('dashboard'); ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            
            <?php if ($role == 'admin'): ?>
                 
                
                <a class="nav-link <?= ($this->uri->segment(2) == 'tahunajaran') ? 'active' : ''; ?>" href="<?= site_url('admin/tahunajaran'); ?>">
                    <i class="fas fa-calendar-alt"></i> Tahun Ajaran
                </a>
                
				<a class="nav-link <?= ($this->uri->segment(2) == 'guru') ? 'active' : ''; ?>" href="<?= site_url('admin/guru'); ?>">
                    <i class="fas fa-chalkboard-teacher"></i> Guru
                </a>
                
				<a class="nav-link <?= ($this->uri->segment(2) == 'siswa') ? 'active' : ''; ?>" href="<?= site_url('admin/siswa'); ?>">
                    <i class="fas fa-user-graduate"></i> Siswa
                </a>
				
                <a class="nav-link <?= ($this->uri->segment(2) == 'kelas') ? 'active' : ''; ?>" href="<?= site_url('admin/kelas'); ?>">
                    <i class="fas fa-chalkboard"></i> Kelas
                </a>
                 
                 
				<a class="nav-link <?= ($this->uri->segment(2) == 'kelassiswa') ? 'active' : ''; ?>" href="<?= site_url('admin/kelassiswa'); ?>">
                    <i class="fas fa-users"></i> Penempatan Siswa
                </a>
                 
				<a class="nav-link <?= ($this->uri->segment(2) == 'matapelajaran') ? 'active' : ''; ?>" href="<?= site_url('admin/matapelajaran'); ?>">
                    <i class="fas fa-book"></i> Mata Pelajaran
                </a>
				
				
                <a class="nav-link <?= ($this->uri->segment(2) == 'jadwal') ? 'active' : ''; ?>" href="<?= site_url('admin/jadwal'); ?>">
                    <i class="fas fa-clock"></i> Jadwal
                </a>
                
                
                <a class="nav-link <?= ($this->uri->segment(2) == 'import') ? 'active' : ''; ?>" href="<?= site_url('admin/import'); ?>">
                    <i class="fas fa-file-excel"></i> Import Data
                </a>
                
            <?php elseif ($role == 'guru'): ?>
                
                
                <a class="nav-link <?= ($this->uri->segment(2) == 'jadwal') ? 'active' : ''; ?>" href="<?= site_url('guru/jadwal'); ?>">
                    <i class="fas fa-calendar-week"></i> Jadwal Mengajar
                </a>
                
                <a class="nav-link <?= ($this->uri->segment(2) == 'presensi') ? 'active' : ''; ?>" href="<?= site_url('guru/presensi'); ?>">
                    <i class="fas fa-clipboard-check"></i> Input Presensi
                </a>
                
                <a class="nav-link <?= ($this->uri->segment(2) == 'rekap') ? 'active' : ''; ?>" href="<?= site_url('guru/rekap'); ?>">
                    <i class="fas fa-file-alt"></i> Rekap & Laporan
                </a>
                
            <?php elseif ($role == 'kepsek'): ?>
                
                
                <a class="nav-link <?= ($this->uri->segment(2) == 'approval') ? 'active' : ''; ?>" href="<?= site_url('kepsek/approval'); ?>">
                    <i class="fas fa-check-double"></i> Approval Presensi
                </a>
                
                <a class="nav-link <?= ($this->uri->segment(2) == 'laporan') ? 'active' : ''; ?>" href="<?= site_url('kepsek/laporan'); ?>">
                    <i class="fas fa-chart-bar"></i> Laporan Presensi
                </a>
                
                <a class="nav-link <?= ($this->uri->segment(2) == 'logapproval') ? 'active' : ''; ?>" href="<?= site_url('kepsek/logapproval'); ?>">
                    <i class="fas fa-history"></i> Log Approval
                </a>
                
            <?php elseif ($role == 'siswa'): ?>
              
                
                <a class="nav-link <?= ($this->uri->segment(2) == 'jadwal') ? 'active' : ''; ?>" href="<?= site_url('siswa/jadwal'); ?>">
                    <i class="fas fa-calendar-alt"></i> Jadwal Pelajaran
                </a>
                
                <a class="nav-link <?= ($this->uri->segment(2) == 'riwayat') ? 'active' : ''; ?>" href="<?= site_url('siswa/riwayat'); ?>">
                    <i class="fas fa-history"></i> Riwayat Presensi
                </a>
                
            <?php endif; ?>
            
            <!-- Mobile Only Menu (Edit Profile & Logout) -->
            <div class="mobile-only-menu d-md-none mt-auto pt-3 border-top border-secondary">
                <a class="nav-link" href="<?= site_url('auth/edit_profile'); ?>">
                    <i class="fas fa-user-edit"></i> Edit Profile
                </a>
                <a class="nav-link" href="<?= site_url('auth/logout'); ?>">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
            
        </nav>
    </div>
