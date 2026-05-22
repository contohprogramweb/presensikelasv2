<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-bar-graph"></i> <?= $page_title ?></h5>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="get" action="<?= site_url('guru/rekap') ?>" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Kelas</label>
                                <select name="kelas" class="form-select" required>
                                    <option value="">Pilih Kelas</option>
                                    <?php foreach ($kelas_list as $k): ?>
                                        <option value="<?= $k->id_kelas ?>" <?= isset($filter_kelas) && $filter_kelas == $k->id_kelas ? 'selected' : '' ?>>
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
                                <?php if (!empty($rekap)): ?>
                                    <a href="<?= site_url('guru/rekap/preview_pdf?kelas='.$filter_kelas.'&start_date='.$filter_start.'&end_date='.$filter_end) ?>" class="btn btn-info" target="_blank">
                                        <i class="bi bi-eye"></i> Preview PDF
                                    </a>
                                    <a href="<?= site_url('guru/rekap/export_pdf?kelas='.$filter_kelas.'&start_date='.$filter_start.'&end_date='.$filter_end) ?>" class="btn btn-danger">
                                        <i class="bi bi-download"></i> Download PDF
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>

                    <!-- Hasil Rekap -->
                    <?php if (!empty($rekap)): ?>
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
                                    $grand_hadir = 0;
                                    $grand_izin = 0;
                                    $grand_sakit = 0;
                                    $grand_alpa = 0;
                                    
                                    foreach ($rekap as $r): 
                                        $total = $r->hadir + $r->izin + $r->sakit + $r->alpa;
                                        $persen = $total > 0 ? round(($r->hadir / $total) * 100, 1) : 0;
                                        
                                        $grand_hadir += $r->hadir;
                                        $grand_izin += $r->izin;
                                        $grand_sakit += $r->sakit;
                                        $grand_alpa += $r->alpa;
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= html_escape($r->nama_siswa) ?></td>
                                            <td class="text-center"><span class="badge bg-success"><?= $r->hadir ?></span></td>
                                            <td class="text-center"><span class="badge bg-info"><?= $r->izin ?></span></td>
                                            <td class="text-center"><span class="badge bg-warning text-dark"><?= $r->sakit ?></span></td>
                                            <td class="text-center"><span class="badge bg-danger"><?= $r->alpa ?></span></td>
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
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="2" class="text-end">Total</th>
                                        <th class="text-center"><span class="badge bg-success"><?= $grand_hadir ?></span></th>
                                        <th class="text-center"><span class="badge bg-info"><?= $grand_izin ?></span></th>
                                        <th class="text-center"><span class="badge bg-warning text-dark"><?= $grand_sakit ?></span></th>
                                        <th class="text-center"><span class="badge bg-danger"><?= $grand_alpa ?></span></th>
                                        <th class="text-center"><?= $grand_hadir + $grand_izin + $grand_sakit + $grand_alpa ?></th>
                                        <th>-</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php elseif (isset($filter_kelas)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Tidak ada data presensi untuk periode yang dipilih.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> Silakan pilih filter dan klik Tampilkan untuk melihat rekap presensi.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
