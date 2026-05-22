<div class="content-wrapper">
<div class="container-fluid">
    <div class="page-heading">
        <h2><i class="fas fa-clipboard-check me-2"></i><?= $page_title ?? 'Input Presensi'; ?></h2>
    </div>
    
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Jadwal</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="150"><strong>Mata Pelajaran</strong></td>
                            <td>: <?= html_escape($jadwal['nama_mapel'] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Kelas</strong></td>
                            <td>: <?= html_escape($jadwal['nama_kelas'] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Hari</strong></td>
                            <td>: <?= html_escape($jadwal['hari'] ?? '-'); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="150"><strong>Jam</strong></td>
                            <td>: <?= date('H:i', strtotime($jadwal['jam_mulai'] ?? '00:00')) ?> - <?= date('H:i', strtotime($jadwal['jam_selesai'] ?? '00:00')) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Ruangan</strong></td>
                            <td>: <?= html_escape($jadwal['ruangan'] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal</strong></td>
                            <td>: <?= tanggal_indo($tanggal ?? date('Y-m-d')); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form action="<?= site_url('guru/presensi/simpan'); ?>" method="post" id="formPresensi">
        <?= form_hidden('id_jadwal', $jadwal['id'] ?? ''); ?>
        <?= form_hidden('tanggal', $tanggal ?? date('Y-m-d')); ?>
        
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Materi Pelajaran</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <textarea name="materi_pelajaran" class="form-control" rows="3" placeholder="Tulis materi pelajaran yang diajarkan hari ini..."><?= set_value('materi_pelajaran'); ?></textarea>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Daftar Siswa</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Nama Siswa</th>
                                <th width="15%">NIS</th>
                                <th width="45%" colspan="4">Status Kehadiran</th>
                                <th width="15%">Keterangan</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th width="10%"><label class="radio-inline"><input type="radio" name="status_all" value="Hadir" class="status-all"> Hadir</label></th>
                                <th width="10%"><label class="radio-inline"><input type="radio" name="status_all" value="Izin" class="status-all"> Izin</label></th>
                                <th width="10%"><label class="radio-inline"><input type="radio" name="status_all" value="Sakit" class="status-all"> Sakit</label></th>
                                <th width="10%"><label class="radio-inline"><input type="radio" name="status_all" value="Alpa" class="status-all"> Alpa</label></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($siswa_list)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Tidak ada siswa di kelas ini</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($siswa_list as $siswa): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td>
                                            <strong><?= html_escape($siswa['nama_lengkap']); ?></strong>
                                            <br><small class="text-muted"><?= ($siswa['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?></small>
                                        </td>
                                        <td><?= html_escape($siswa['nis']); ?></td>
                                        <td>
                                            <label class="radio-inline">
                                                <input type="radio" name="siswa[<?= $siswa['id']; ?>][status]" value="Hadir" checked> H
                                            </label>
                                        </td>
                                        <td>
                                            <label class="radio-inline">
                                                <input type="radio" name="siswa[<?= $siswa['id']; ?>][status]" value="Izin"> I
                                            </label>
                                        </td>
                                        <td>
                                            <label class="radio-inline">
                                                <input type="radio" name="siswa[<?= $siswa['id']; ?>][status]" value="Sakit"> S
                                            </label>
                                        </td>
                                        <td>
                                            <label class="radio-inline">
                                                <input type="radio" name="siswa[<?= $siswa['id']; ?>][status]" value="Alpa"> A
                                            </label>
                                        </td>
                                        <td>
                                            <input type="text" name="siswa[<?= $siswa['id']; ?>][keterangan]" class="form-control form-control-sm" placeholder="Keterangan...">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body text-end">
                <a href="<?= site_url('guru/presensi'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin menyimpan data presensi ini?')">
                    <i class="fas fa-save me-1"></i>Simpan Presensi
                </button>
            </div>
        </div>
    </form>
</div>
</div>

<script>
$(document).ready(function() {
    // Fitur select all status
    $('input[name="status_all"]').change(function() {
        var statusValue = $(this).val();
        $('input[name^="siswa["][name$="[status]"]').each(function() {
            if ($(this).val() === statusValue) {
                $(this).prop('checked', true);
            }
        });
    });

    // Auto hide alert after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
