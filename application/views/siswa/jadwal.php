<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-week"></i> <?= $page_title ?></h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> <?= html_escape($error_message) ?>
                        </div>
                    <?php elseif (empty($jadwal_grouped['Senin']) && empty($jadwal_grouped['Selasa']) && empty($jadwal_grouped['Rabu']) && empty($jadwal_grouped['Kamis']) && empty($jadwal_grouped['Jumat']) && empty($jadwal_grouped['Sabtu'])): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Belum ada jadwal pelajaran untuk kelas Anda.
                        </div>
                    <?php else: ?>
                        <div class="accordion" id="accordionJadwalSiswa">
                            <?php foreach ($hari_list as $index => $hari): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading<?= $hari ?>">
                                        <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $hari ?>" aria-expanded="<?= $index == 0 ? 'true' : 'false' ?>">
                                            <i class="bi bi-calendar-day me-2"></i> <?= $hari ?>
                                            <?php if (!empty($jadwal_grouped[$hari])): ?>
                                                <span class="badge bg-primary ms-2"><?= count($jadwal_grouped[$hari]) ?> Jam Pelajaran</span>
                                            <?php endif; ?>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= $hari ?>" class="accordion-collapse collapse <?= $index == 0 ? 'show' : '' ?>" data-bs-parent="#accordionJadwalSiswa">
                                        <div class="accordion-body">
                                            <?php if (empty($jadwal_grouped[$hari])): ?>
                                                <p class="text-muted mb-0">Tidak ada jadwal pada hari <?= $hari ?>.</p>
                                            <?php else: ?>
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th width="5%">No</th>
                                                                <th>Jam</th>
                                                                <th>Mata Pelajaran</th>
                                                                <th>Guru Pengampu</th>
                                                                <th>Ruangan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                            $no = 1;
                                                            foreach ($jadwal_grouped[$hari] as $j): 
                                                            ?>
                                                                <tr>
                                                                    <td><?= $no++ ?></td>
                                                                    <td>
                                                                        <strong><?= date('H:i', strtotime($j->jam_mulai)) ?> - <?= date('H:i', strtotime($j->jam_selesai)) ?></strong>
                                                                    </td>
                                                                    <td><?= html_escape($j->nama_mapel) ?></td>
                                                                    <td><?= html_escape($j->nama_guru_lengkap ?? $j->nama_guru ?? '-') ?></td>
                                                                    <td><?= html_escape($j->ruangan ?? '-') ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.accordion-button:not(.collapsed) {
    background-color: #0d6efd;
    color: white;
}
.accordion-button:not(.collapsed)::after {
    filter: invert(1);
}
</style>
