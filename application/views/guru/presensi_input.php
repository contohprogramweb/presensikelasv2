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
                    <textarea name="materi_pelajaran" class="form-control" rows="3" placeholder="Tulis materi pelajaran yang diajarkan hari ini..." required><?= set_value('materi_pelajaran'); ?></textarea>
                    <small class="text-muted">Materi pelajaran wajib diisi sebelum menyimpan presensi.</small>
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
                                            <input type="text" name="siswa[<?= $siswa['id']; ?>][keterangan]" class="form-control form-control-sm keterangan-siswa" placeholder="Keterangan...">
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
                <button type="submit" class="btn btn-primary" id="btnSimpanPresensi">
                    <i class="fas fa-save me-1"></i>Simpan Presensi
                </button>
            </div>
        </div>
    </form>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Handle radio button "select all" untuk status
    $('input[name="status_all"]').on('change', function() {
        var selectedStatus = $(this).val();
        $('input[name^="siswa"][name$="[status]"][value="' + selectedStatus + '"]').prop('checked', true);
    });

    // Handle form submit dengan AJAX
    $('#formPresensi').on('submit', function(e) {
        e.preventDefault();
        
        // Validasi materi pelajaran
        var materiPelajaran = $('textarea[name="materi_pelajaran"]').val().trim();
        if (materiPelajaran === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Materi pelajaran wajib diisi!',
                confirmButtonText: 'OK'
            });
            return false;
        }
        
        // Disable tombol submit
        var btnSubmit = $('#btnSimpanPresensi');
        btnSubmit.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...');
        
        // Kirim data via AJAX
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function() {
                // Pastikan response diharapkan sebagai JSON
                $.ajaxSetup({
                    headers: {
                        'Accept': 'application/json'
                    }
                });
            },
            success: function(response) {
                // Validasi response adalah object JSON
                if (typeof response !== 'object') {
                    console.error('Response bukan JSON:', response);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Response server tidak valid. Silakan coba lagi.',
                        confirmButtonText: 'OK'
                    });
                    btnSubmit.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Simpan Presensi');
                    return;
                }
                
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(function() {
                        // Redirect setelah user klik OK
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message || 'Terjadi kesalahan saat menyimpan presensi.',
                        confirmButtonText: 'OK'
                    });
                    btnSubmit.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Simpan Presensi');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.error('Response Text:', xhr.responseText.substring(0, 500));
                
                var errorMessage = 'Terjadi kesalahan pada server.';
                
                // Coba parse response manual jika ada
                if (xhr.responseText) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch(e) {
                        // Response bukan JSON, tampilkan info error
                        if (xhr.status === 401) {
                            errorMessage = 'Sesi telah berakhir. Silakan refresh halaman dan login kembali.';
                        } else if (xhr.status === 403) {
                            errorMessage = 'Akses ditolak.';
                        } else {
                            errorMessage = 'Server error (' + xhr.status + '): ' + xhr.responseText.substring(0, 200);
                        }
                    }
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonText: 'OK'
                });
                btnSubmit.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Simpan Presensi');
            }
        });
        
        return false;
    });
});
</script>