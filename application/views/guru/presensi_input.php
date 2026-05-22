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
            <i class="fas fa-exclamation-triangle me-2"></i>Presensi untuk tanggal ini sudah pernah diisi. Data yang ditampilkan adalah data terakhir yang disimpan.
        </div>
    <?php endif; ?>

    <form id="formPresensi">
        <input type="hidden" id="csrf_name"  value="<?= $this->security->get_csrf_token_name(); ?>">
        <input type="hidden" id="csrf_hash"  value="<?= $this->security->get_csrf_hash(); ?>">
        <input type="hidden" id="id_jadwal"  value="<?= encrypt_id($jadwal['id']); ?>">
        <input type="hidden" id="tgl_presensi" value="<?= $tanggal; ?>">

        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-book-open me-2"></i>Materi Pelajaran</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="materi_pelajaran" class="form-label">Materi yang Diajarkan *</label>
                    <textarea class="form-control" id="materi_pelajaran" name="materi_pelajaran"
                              rows="3" required minlength="5"><?= html_escape(
                        $this->input->post('materi_pelajaran') ??
                        ($existing_presensi[0]['materi_pelajaran'] ?? '')
                    ); ?></textarea>
                    <small class="text-muted">Minimal 5 karakter</small>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="fas fa-users me-2"></i>Daftar Siswa</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="35%">Nama Siswa</th>
                                <th width="20%">Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($siswa)): ?>
                                <tr><td colspan="4" class="text-center text-danger">Tidak ada siswa di kelas ini.</td></tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($siswa as $s):
                                    $existing = null;
                                    foreach ($existing_presensi as $ep) {
                                        if ($ep['id_siswa'] == $s['id']) { $existing = $ep; break; }
                                    }
                                    $cur_status = $existing['status'] ?? 'Hadir';
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td>
                                        <input type="hidden" class="siswa-id"   value="<?= $s['id']; ?>">
                                        <?= html_escape($s['nama_lengkap']); ?>
                                        <?php if (!empty($s['nis'])): ?>
                                            <br><small class="text-muted">NIS: <?= html_escape($s['nis']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm status-select" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="Hadir" <?= $cur_status==='Hadir'?'selected':''; ?>>Hadir</option>
                                            <option value="Izin"  <?= $cur_status==='Izin' ?'selected':''; ?>>Izin</option>
                                            <option value="Sakit" <?= $cur_status==='Sakit'?'selected':''; ?>>Sakit</option>
                                            <option value="Alpa"  <?= $cur_status==='Alpa' ?'selected':''; ?>>Alpa</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea class="form-control form-control-sm keterangan-input" rows="2"
                                            placeholder="<?= in_array($cur_status,['Izin','Sakit'])?'Wajib diisi (min. 10 karakter)':'Opsional'; ?>"
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

        <div class="mt-3 mb-4">
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
$(document).ready(function () {

    // Tampilkan/sembunyikan keterangan required saat status berubah
    $(document).on('change', '.status-select', function () {
        var status = $(this).val();
        var ket    = $(this).closest('tr').find('.keterangan-input');
        if (status === 'Izin' || status === 'Sakit') {
            ket.attr('placeholder', 'Wajib diisi (min. 10 karakter)').attr('required', true);
        } else {
            ket.attr('placeholder', 'Opsional').removeAttr('required').val('');
        }
    });

    // Submit via AJAX — bangun payload manual (tanpa FormData agar lebih kompatibel)
    $('#formPresensi').on('submit', function (e) {
        e.preventDefault();

        // Validasi semua status sudah dipilih
        var hasError = false;
        $('.status-select').each(function () {
            if (!$(this).val()) { hasError = true; $(this).addClass('is-invalid'); }
            else                { $(this).removeClass('is-invalid'); }
        });
        if (hasError) {
            Swal.fire({ icon: 'error', title: 'Validasi', text: 'Pilih status kehadiran untuk semua siswa.' });
            return;
        }

        var materi = $.trim($('#materi_pelajaran').val());
        if (materi.length < 5) {
            Swal.fire({ icon: 'error', title: 'Validasi', text: 'Materi pelajaran minimal 5 karakter.' });
            return;
        }

        // Kumpulkan data siswa
        var siswaIds   = [];
        var statusArr  = [];
        var ketArr     = [];

        $('tbody tr').each(function () {
            var row = $(this);
            siswaIds.push(row.find('.siswa-id').val());
            statusArr.push(row.find('.status-select').val());
            ketArr.push(row.find('.keterangan-input').val());
        });

        // Bangun payload sebagai serialized string
        var csrfName  = $('#csrf_name').val();
        var csrfHash  = $('#csrf_hash').val();
        var idJadwal  = $('#id_jadwal').val();
        var tanggal   = $('#tgl_presensi').val();

        var payload = {};
        payload[csrfName]      = csrfHash;
        payload['id_jadwal']   = idJadwal;
        payload['tanggal']     = tanggal;
        payload['materi_pelajaran'] = materi;

        // Tambahkan array siswa
        for (var i = 0; i < siswaIds.length; i++) {
            payload['id_siswa[' + i + ']']   = siswaIds[i];
            payload['status[' + i + ']']     = statusArr[i];
            payload['keterangan[' + i + ']'] = ketArr[i];
        }

        var btn = $('#btnSimpan');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');

        $.ajax({
            url        : '<?= site_url('guru/presensi/simpan'); ?>',
            type       : 'POST',
            data       : payload,
            dataType   : 'json',
            success    : function (res) {
                if (res && res.expired) {
                    Swal.fire({
                        icon : 'warning',
                        title: 'Sesi Berakhir',
                        text : res.message,
                        confirmButtonText: 'Login Kembali'
                    }).then(function () {
                        window.location.href = '<?= site_url('auth/login'); ?>';
                    });
                    return;
                }
                if (res && res.status) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message })
                        .then(function () { window.location.href = '<?= site_url('guru/presensi'); ?>'; });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res ? res.message : 'Terjadi kesalahan.' });
                    btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Simpan Presensi');
                }
            },
            error      : function (xhr) {
                var msg = 'Terjadi kesalahan. Status: ' + xhr.status;
                // Coba parse JSON error dari server
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.expired) {
                        Swal.fire({
                            icon : 'warning',
                            title: 'Sesi Berakhir',
                            text : res.message,
                            confirmButtonText: 'Login Kembali'
                        }).then(function () {
                            window.location.href = '<?= site_url('auth/login'); ?>';
                        });
                        return;
                    }
                    if (res.message) msg = res.message;
                } catch (ex) { /* bukan JSON */ }
                Swal.fire({ icon: 'error', title: 'Error ' + xhr.status, text: msg });
                btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Simpan Presensi');
            }
        });
    });
});
</script>
