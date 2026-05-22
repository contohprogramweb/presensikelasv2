<div class="content-wrapper">
<div class="container-fluid">
    <div class="page-heading">
        <h2><i class="fas fa-clipboard-check me-2"></i>Input Presensi</h2>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('info')): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i><?= $this->session->flashdata('info'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-calendar-day me-2"></i>Jadwal Hari Ini (<?= $hari_ini_indo ?? 'Hari Ini' ?>)</span>
            <?php if (!empty($tahun_ajaran_aktif)): ?>
                <span class="badge bg-info"><?= $tahun_ajaran_aktif->tahun_ajaran ?> - Semester <?= $tahun_ajaran_aktif->semester ?></span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (empty($jadwal_hari_ini)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>Tidak ada jadwal mengajar hari ini (<?= $hari_ini_indo ?? '' ?>).
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Jam</th>
                                <th>Ruangan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($jadwal_hari_ini as $jadwal): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= html_escape($jadwal->nama_mapel); ?></td>
                                    <td><?= html_escape($jadwal->nama_kelas); ?></td>
                                    <td><?= date('H:i', strtotime($jadwal->jam_mulai)) ?> - <?= date('H:i', strtotime($jadwal->jam_selesai)) ?></td>
                                    <td><?= html_escape($jadwal->ruangan ?? '-'); ?></td>
                                    <td>
                                        <a href="<?= site_url('guru/presensi/form/' . encrypt_id($jadwal->id)); ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit me-1"></i>Input Presensi
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
<script>
$(document).ready(function() {
    // Auto hide alert after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>