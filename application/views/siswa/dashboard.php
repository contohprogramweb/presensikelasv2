<div class="content-wrapper">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h4 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Siswa</h4>
        </div>
    </div>

    <?php if ($not_assigned): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>
                    <strong>Belum Ditugaskan ke Kelas:</strong> Anda belum ditugaskan ke kelas. Silakan hubungi administrator atau guru untuk penugasan kelas.
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Hadir</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($stats['hadir']) ? $stats['hadir'] : 0; ?></h3>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-check-circle fa-2x"></i>
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
                            <h6 class="text-muted mb-1">Izin</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($stats['izin']) ? $stats['izin'] : 0; ?></h3>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-envelope-open-text fa-2x"></i>
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
                            <h6 class="text-muted mb-1">Sakit</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($stats['sakit']) ? $stats['sakit'] : 0; ?></h3>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-bed fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Alpa</h6>
                            <h3 class="mb-0 fw-bold"><?= isset($stats['alpa']) ? $stats['alpa'] : 0; ?></h3>
                        </div>
                        <div class="text-danger">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Siswa -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <span><i class="fas fa-user me-2"></i>Informasi Siswa</span>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="30%">Nama Lengkap</th>
                            <td><?= html_escape($siswa->nama_lengkap); ?></td>
                        </tr>
                        <tr>
                            <th>NIS/NISN</th>
                            <td><?= html_escape($siswa->nis ?? '-'); ?> / <?= html_escape($siswa->nisn ?? '-'); ?></td>
                        </tr>
                        <?php if (!$not_assigned): ?>
                        <tr>
                            <th>Kelas</th>
                            <td>
                                <?php
                                $this->db->select('nama_kelas');
                                $this->db->where('id', $siswa->id_kelas);
                                $kelas = $this->db->get('tb_kelas', 1)->row();
                                echo $kelas ? html_escape($kelas->nama_kelas) : '-';
                                ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <span><i class="fas fa-calendar-alt me-2"></i>Rekap Bulan Ini</span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Statistik presensi Anda pada bulan ini:</p>
                    <?php
                    $total = $stats['hadir'] + $stats['izin'] + $stats['sakit'] + $stats['alpa'];
                    $persentase_hadir = $total > 0 ? round(($stats['hadir'] / $total) * 100, 1) : 0;
                    ?>
                    <div class="progress mb-3" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $persentase_hadir; ?>%"
                             aria-valuenow="<?= $persentase_hadir; ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= $persentase_hadir; ?>%
                        </div>
                    </div>
                    <p class="mb-0"><small>Total hari presensi: <strong><?= $total; ?></strong> hari</small></p>
                </div>
            </div>
        </div>
    </div>

    
</div>
</div>