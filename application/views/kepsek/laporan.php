<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-bar-graph"></i> <?= $page_title ?></h5>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="get" action="<?= site_url('kepsek/laporan') ?>" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Kelas</label>
                                <select name="kelas" class="form-select" required>
                                    <option value="">Pilih Kelas</option>
                                    <?php foreach ($kelas_list as $k): ?>
                                        <option value="<?= $k->id ?>" <?= isset($filter_kelas) && $filter_kelas == $k->id ? 'selected' : '' ?>>
                                            <?= html_escape($k->nama_kelas) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control" value="<?= $filter_start ?? date('Y-m-01') ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Akhir</label>
                                <input type="date" name="end_date" class="form-control" value="<?= $filter_end ?? date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Tampilkan
                                </button>
                                <?php if (!empty($statistik)): ?>
                                    <a href="<?= site_url('kepsek/laporan/preview_pdf?kelas='.$filter_kelas.'&start_date='.$filter_start.'&end_date='.$filter_end) ?>" class="btn btn-danger" target="_blank">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>

                    <!-- Statistik -->
                    <?php if (!empty($statistik)): ?>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h3><?= $statistik->hadir ?></h3>
                                        <p class="mb-0">Hadir</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h3><?= $statistik->izin ?></h3>
                                        <p class="mb-0">Izin</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body text-center">
                                        <h3><?= $statistik->sakit ?></h3>
                                        <p class="mb-0">Sakit</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h3><?= $statistik->alpa ?></h3>
                                        <p class="mb-0">Alpa</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Tabel Laporan -->
                    <?php if (!empty($laporan)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Siswa</th>
                                        <th width="8%" class="text-center">Hadir</th>
                                        <th width="8%" class="text-center">Izin</th>
                                        <th width="8%" class="text-center">Sakit</th>
                                        <th width="8%" class="text-center">Alpa</th>
                                        <th width="8%" class="text-center">Total</th>
                                        <th width="12%" class="text-center">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach ($laporan as $l): 
                                        $total = $l->hadir + $l->izin + $l->sakit + $l->alpa;
                                        $persen = $total > 0 ? round(($l->hadir / $total) * 100, 1) : 0;
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= html_escape($l->nama_siswa) ?></td>
                                            <td class="text-center"><span class="badge bg-success"><?= $l->hadir ?></span></td>
                                            <td class="text-center"><span class="badge bg-info"><?= $l->izin ?></span></td>
                                            <td class="text-center"><span class="badge bg-warning text-dark"><?= $l->sakit ?></span></td>
                                            <td class="text-center"><span class="badge bg-danger"><?= $l->alpa ?></span></td>
                                            <td class="text-center"><strong><?= $total ?></strong></td>
                                            <td class="text-center">
                                                <?php
                                                $badge_class = $persen >= 90 ? 'bg-success' : ($persen >= 75 ? 'bg-warning' : 'bg-danger');
                                                ?>
                                                <span class="badge <?= $badge_class ?>"><?= $persen ?>%</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php elseif (isset($filter_kelas)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Tidak ada data presensi untuk periode yang dipilih.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> Silakan pilih filter dan klik Tampilkan untuk melihat laporan.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
