<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h4 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Guru</h4>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Jadwal Hari Ini</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($stats['jadwal_hari_ini']) ? $stats['jadwal_hari_ini'] : 0; ?></h3>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-calendar-day fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Presensi Bulan Ini</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($stats['presensi_bulan_ini']) ? $stats['presensi_bulan_ini'] : 0; ?></h3>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-clipboard-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Kelas Diampu</h6>
                            <?php
                            $this->db->select('COUNT(DISTINCT id_kelas) as total');
                            $this->db->where('id_guru', $guru->id);
                            $this->db->where('status_aktif', 1);
                            $total_kelas = $this->db->get('tb_jadwal')->row()->total ?? 0;
                            ?>
                            <h3 class="mb-0 fw-bold"><?= $total_kelas; ?></h3>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-chalkboard fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Hari Ini -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-calendar-alt me-2"></i>Jadwal Mengajar Hari Ini</span>
                    <a href="<?= site_url('guru/jadwal'); ?>" class="btn btn-sm btn-light text-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <?php
                    $hari_map = array(
                        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
                    );
                    $hari_ini = $hari_map[date('l')];
                    
                    $this->db->select('tb_jadwal.*, tb_kelas.nama_kelas, tb_mata_pelajaran.nama_mapel');
                    $this->db->join('tb_kelas', 'tb_kelas.id = tb_jadwal.id_kelas');
                    $this->db->join('tb_mata_pelajaran', 'tb_mata_pelajaran.id = tb_jadwal.id_mapel');
                    $this->db->where('tb_jadwal.hari', $hari_ini);
                    $this->db->where('tb_jadwal.id_guru', $guru->id);
                    $this->db->where('tb_jadwal.status_aktif', 1);
                    $this->db->order_by('tb_jadwal.jam_mulai', 'ASC');
                    $jadwal_hari = $this->db->get('tb_jadwal')->result();
                    ?>
                    
                    <?php if (empty($jadwal_hari)): ?>
                        <p class="text-muted mb-0">Tidak ada jadwal mengajar hari ini.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">Jam</th>
                                        <th width="20%">Kelas</th>
                                        <th width="25%">Mata Pelajaran</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($jadwal_hari as $j): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= date('H:i', strtotime($j->jam_mulai)); ?> - <?= date('H:i', strtotime($j->jam_selesai)); ?></td>
                                        <td><?= html_escape($j->nama_kelas); ?></td>
                                        <td><?= html_escape($j->nama_mapel); ?></td>
                                        <td>
                                            <a href="<?= site_url('guru/presensi?jadwal=' . $j->id); ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-clipboard-check me-1"></i>Input Presensi
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
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
                        <div class="col-md-3 col-sm-6 mb-2">
                            <a href="<?= site_url('guru/presensi'); ?>" class="btn btn-outline-primary w-100">
                                <i class="fas fa-clipboard-check d-block mb-1"></i>
                                <small>Input Presensi</small>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <a href="<?= site_url('guru/jadwal'); ?>" class="btn btn-outline-success w-100">
                                <i class="fas fa-calendar-week d-block mb-1"></i>
                                <small>Lihat Jadwal</small>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <a href="<?= site_url('guru/rekap'); ?>" class="btn btn-outline-info w-100">
                                <i class="fas fa-file-alt d-block mb-1"></i>
                                <small>Rekap & Laporan</small>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <a href="<?= site_url('profil'); ?>" class="btn btn-outline-dark w-100">
                                <i class="fas fa-user-cog d-block mb-1"></i>
                                <small>Profil Saya</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
