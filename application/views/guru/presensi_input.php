<div class="content-wrapper">

<div class="container-fluid">
    <div class="page-heading">
        <h2><i class="fas fa-edit me-2"></i>Form Presensi</h2>
        <p class="text-muted">
            <?= html_escape($jadwal['nama_mapel']); ?> - Kelas <?= html_escape($jadwal['nama_kelas']); ?>
            <br>Tanggal: <?= date('d F Y', strtotime($tanggal)); ?>
        </p>
    </div>

    <?php if (!empty($existing_presensi)): ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>Presensi untuk tanggal ini sudah pernah diisi.
            Data yang ditampilkan adalah data terakhir yang disimpan.
        </div>
    <?php endif; ?>

    <form id="formPresensi" method="post" action="<?= site_url('guru/presensi/simpan'); ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <input type="hidden" name="id_jadwal" value="<?= encrypt_id($jadwal['id']); ?>">
        <input type="hidden" name="tanggal" value="<?= $tanggal; ?>">

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-book-open me-2"></i>Materi Pelajaran
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="materi_pelajaran" class="form-label">Materi yang Diajarkan *</label>
                    <textarea class="form-control" id="materi_pelajaran" name="materi_pelajaran" rows="3" required minlength="5"><?= html_escape($this->input->post('materi_pelajaran') ?? ($existing_presensi[0]['materi_pelajaran'] ?? '')); ?></textarea>
                    <small class="text-muted">Minimal 5 karakter</small>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-users me-2"></i>Daftar Siswa
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tabelSiswa">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="30%">Nama Siswa</th>
                                <th width="15%">Status Kehadiran</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($siswa)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-danger">
                                        <i class="fas fa-info-circle me-2"></i>Tidak ada siswa di kelas ini.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($siswa as $s):
                                    $existing = null;
                                    if (!empty($existing_presensi)) {
                                        foreach ($existing_presensi as $ep) {
                                            if ($ep['id_siswa'] == $s['id']) {
                                                $existing = $ep;
                                                break;
                                            }
                                        }
                                    }
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td>
                                            <input type="hidden" name="id_siswa[]" value="<?= $s['id']; ?>">
                                            <?= html_escape($s['nama_lengkap']); ?>
                                            <?php if ($s['nis']): ?>
                                                <br><small class="text-muted">NIS: <?= html_escape($s['nis']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm status-select" name="status[]" required data-siswa="<?= $s['id']; ?>">
                                                <option value="">-- Pilih Status --</option>
                                                <option value="Hadir" <?= (isset($existing) && $existing['status'] == 'Hadir') ? 'selected' : ''; ?>>Hadir</option>
                                                <option value="Izin" <?= (isset($existing) && $existing['status'] == 'Izin') ? 'selected' : ''; ?>>Izin</option>
                                                <option value="Sakit" <?= (isset($existing) && $existing['status'] == 'Sakit') ? 'selected' : ''; ?>>Sakit</option>
                                                <option value="Alpa" <?= (isset($existing) && $existing['status'] == 'Alpa') ? 'selected' : ''; ?>>Alpa</option>
                                            </select>
                                        </td>
                                        <td>
                                            <textarea class="form-control form-control-sm keterangan-input"
                                                      name="keterangan[]"
                                                      rows="2"
                                                      placeholder="Keterangan (wajib untuk Izin/Sakit)"
                                                      <?= (isset($existing) && in_array($existing['status'], ['Izin', 'Sakit'])) ? 'required' : ''; ?>
                                                      minlength="10"><?= html_escape($existing['keterangan'] ?? ''); ?></textarea>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="<?= site_url('guru/presensi'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <button type="submit" class="btn btn-primary" id="btnSimpan">
                <i class="fas fa-save me-2"></i>Simpan Presensi
            </button>
        </div>
    </form>
</div>

</div>

<script>
$(document).ready(function() {
    // Handle perubahan status untuk menampilkan/menyembunyikan required pada keterangan
    $('.status-select').on('change', function() {
        var status = $(this).val();
        var row = $(this).closest('tr');
        var keteranganInput = row.find('.keterangan-input');

        if (status === 'Izin' || status === 'Sakit') {
            keteranganInput.prop('required', true);
            keteranganInput.attr('placeholder', 'Keterangan wajib diisi (min. 10 karakter)');
        } else {
            keteranganInput.prop('required', false);
            keteranganInput.attr('placeholder', 'Keterangan (opsional)');
        }
    });

    // Form submission dengan AJAX
    $('#formPresensi').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var btnSimpan = $('#btnSimpan');

        // Validasi client-side
        var hasError = false;
        $('select[name="status[]"]').each(function() {
            if (!$(this).val()) {
                hasError = true;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (hasError) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Mohon pilih status kehadiran untuk semua siswa.'
            });
            return;
        }

        btnSimpan.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');

        $.ajax({
            url: '<?= site_url('guru/presensi/simpan'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(function() {
                        window.location.href = '<?= site_url('guru/presensi'); ?>';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                    btnSimpan.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Simpan Presensi');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat menyimpan data.'
                });
                btnSimpan.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Simpan Presensi');
            }
        });
    });
});
</script>