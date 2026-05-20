<div class="content-wrapper">

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h4 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin</h4>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Siswa Aktif</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($stats['total_siswa']) ? $stats['total_siswa'] : 0; ?></h3>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-user-graduate fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Guru Aktif</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($stats['total_guru']) ? $stats['total_guru'] : 0; ?></h3>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Kelas Aktif</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($stats['total_kelas']) ? $stats['total_kelas'] : 0; ?></h3>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-chalkboard fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Presensi Hari Ini</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($stats['presensi_hari_ini']) ? $stats['presensi_hari_ini'] : 0; ?></h3>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Pending & Presensi Hari Ini -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-clock me-2"></i>Approval Pending</span>
                    <a href="<?= site_url('kepsek/approval'); ?>" class="btn btn-sm btn-light text-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <?php if (isset($stats['approval_pending']) && $stats['approval_pending'] > 0): ?>
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Terdapat <strong><?= $stats['approval_pending']; ?></strong> presensi Izin/Sakit yang menunggu approval.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            Tidak ada approval pending.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <span><i class="fas fa-chart-pie me-2"></i>Ringkasan Presensi Hari Ini</span>
                </div>
                <div class="card-body">
                    <?php if (isset($stats['ringkasan_hari_ini'])): ?>
                        <div class="row text-center">
                            <div class="col-3">
                                <span class="badge bg-success p-2 w-100">Hadir<br><?= $stats['ringkasan_hari_ini']['hadir'] ?? 0; ?></span>
                            </div>
                            <div class="col-3">
                                <span class="badge bg-info p-2 w-100">Izin<br><?= $stats['ringkasan_hari_ini']['izin'] ?? 0; ?></span>
                            </div>
                            <div class="col-3">
                                <span class="badge bg-warning p-2 w-100">Sakit<br><?= $stats['ringkasan_hari_ini']['sakit'] ?? 0; ?></span>
                            </div>
                            <div class="col-3">
                                <span class="badge bg-danger p-2 w-100">Alpa<br><?= $stats['ringkasan_hari_ini']['alpa'] ?? 0; ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Belum ada data presensi hari ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <span><i class="fas fa-bolt me-2"></i>Aksi Cepat</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-4 mb-2">
                            <a href="<?= site_url('admin/siswa'); ?>" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-graduate d-block mb-1"></i>
                                <small>Kelola Siswa</small>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-2">
                            <a href="<?= site_url('admin/guru'); ?>" class="btn btn-outline-success w-100">
                                <i class="fas fa-chalkboard-teacher d-block mb-1"></i>
                                <small>Kelola Guru</small>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-2">
                            <a href="<?= site_url('admin/kelas'); ?>" class="btn btn-outline-info w-100">
                                <i class="fas fa-chalkboard d-block mb-1"></i>
                                <small>Kelola Kelas</small>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-2">
                            <a href="<?= site_url('admin/jadwal'); ?>" class="btn btn-outline-warning w-100">
                                <i class="fas fa-calendar-alt d-block mb-1"></i>
                                <small>Jadwal</small>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-2">
                            <a href="<?= site_url('admin/import'); ?>" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-file-excel d-block mb-1"></i>
                                <small>Import</small>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-2">
                            <a href="<?= site_url('profil'); ?>" class="btn btn-outline-dark w-100">
                                <i class="fas fa-user-cog d-block mb-1"></i>
                                <small>Profil</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>