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

    // Setup CSRF Token untuk semua request AJAX
    $.ajaxSetup({
        beforeSend: function(xhr, settings) {
            if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type) && !this.crossDomain) {
                var csrfName = $('meta[name="csrf_name"]').attr('content');
                var csrfHash = $('meta[name="csrf_hash"]').attr('content');
                if (csrfName && csrfHash) {
                    // Set header custom untuk CSRF
                    xhr.setRequestHeader(csrfName, csrfHash);
                    // Juga tambahkan sebagai header X-CSRF-TOKEN untuk kompatibilitas
                    xhr.setRequestHeader('X-CSRF-TOKEN', csrfHash);
                }
            }
        }
    });

    // Validasi form sebelum submit dengan SweetAlert2
    $('#formPresensi').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var materiPelajaran = form.find('textarea[name="materi_pelajaran"]').val().trim();
        
        // Validasi materi pelajaran
        if (!materiPelajaran) {
            Swal.fire({
                icon: 'warning',
                title: 'Materi Pelajaran Kosong',
                text: 'Silakan isi materi pelajaran yang diajarkan hari ini sebelum menyimpan presensi.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#f39c12'
            });
            return false;
        }
        
        // Hitung jumlah siswa
        var jumlahSiswa = $('input[name^="siswa["][name$="[status]"]:checked').length;
        
        if (jumlahSiswa === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Tidak Ada Data Siswa',
                text: 'Tidak ada siswa yang dapat diproses untuk presensi ini.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#e74c3c'
            });
            return false;
        }
        
        // Hitung distribusi kehadiran
        var hadir = $('input[name^="siswa["][name$="[status]"][value="Hadir"]:checked').length;
        var izin = $('input[name^="siswa["][name$="[status]"][value="Izin"]:checked').length;
        var sakit = $('input[name^="siswa["][name$="[status]"][value="Sakit"]:checked').length;
        var alpa = $('input[name^="siswa["][name$="[status]"][value="Alpa"]:checked').length;
        
        // Tampilkan konfirmasi dengan ringkasan
        Swal.fire({
            title: 'Konfirmasi Simpan Presensi',
            html: '<div class="text-start">' +
                  '<p>Apakah Anda yakin ingin menyimpan data presensi ini?</p>' +
                  '<hr>' +
                  '<div class="row">' +
                  '<div class="col-6"><strong>Total Siswa:</strong></div>' +
                  '<div class="col-6 text-end">' + jumlahSiswa + ' siswa</div>' +
                  '</div>' +
                  '<div class="row">' +
                  '<div class="col-6"><span class="badge bg-success">Hadir</span></div>' +
                  '<div class="col-6 text-end">' + hadir + ' siswa</div>' +
                  '</div>' +
                  '<div class="row">' +
                  '<div class="col-6"><span class="badge bg-info">Izin</span></div>' +
                  '<div class="col-6 text-end">' + izin + ' siswa</div>' +
                  '</div>' +
                  '<div class="row">' +
                  '<div class="col-6"><span class="badge bg-warning">Sakit</span></div>' +
                  '<div class="col-6 text-end">' + sakit + ' siswa</div>' +
                  '</div>' +
                  '<div class="row">' +
                  '<div class="col-6"><span class="badge bg-danger">Alpa</span></div>' +
                  '<div class="col-6 text-end">' + alpa + ' siswa</div>' +
                  '</div>' +
                  '</div>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2ecc71',
            cancelButtonColor: '#95a5a6',
            confirmButtonText: '<i class="fas fa-check me-1"></i>Ya, Simpan',
            cancelButtonText: '<i class="fas fa-times me-1"></i>Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                simpanPresensiAJAX(form);
            }
        });
        
        return false;
    });
    
    function simpanPresensiAJAX(form) {
        // Disable button dan tampilkan loading
        var btnSubmit = $('#btnSimpanPresensi');
        var originalText = btnSubmit.html();
        btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');
        
        // Kumpulkan data siswa dalam format object
        var siswaData = {};
        $('input[name^="siswa["][name$="[status]"]:checked').each(function() {
            var nameParts = $(this).attr('name').match(/siswa\[(\d+)\]\[status\]/);
            if (nameParts && nameParts[1]) {
                var idSiswa = nameParts[1];
                var status = $(this).val();
                var keterangan = form.find('input[name="siswa[' + idSiswa + '][keterangan]"]').val() || '';
                
                siswaData[idSiswa] = {
                    status: status,
                    keterangan: keterangan
                };
            }
        });
        
        // Kirim data sebagai FormData tanpa CSRF token
        var formData = new FormData();
        formData.append('id_jadwal', form.find('input[name="id_jadwal"]').val());
        formData.append('tanggal', form.find('input[name="tanggal"]').val());
        formData.append('materi_pelajaran', form.find('textarea[name="materi_pelajaran"]').val());

        // Append setiap siswa secara individual
        $.each(siswaData, function(idSiswa, data) {
            formData.append('siswa[' + idSiswa + '][status]', data.status);
            formData.append('siswa[' + idSiswa + '][keterangan]', data.keterangan || '');
        });

        // Kirim data sebagai FormData
        $.ajax({
            url: '<?= site_url("guru/presensi/simpan") ?>',
            type: 'POST',
            data: formData,
            processData: false,  // Jangan proses data
            contentType: false,  // Jangan set content type otomatis
            dataType: 'json',
            success: function(response) {
                btnSubmit.prop('disabled', false).html(originalText);
                
                if (response.status === 'success' || response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.pesan || response.message || 'Data presensi berhasil disimpan.',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        window.location.href = '<?= site_url("guru/presensi") ?>';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.pesan || response.message || 'Terjadi kesalahan saat menyimpan data.',
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function(xhr, status, error) {
                btnSubmit.prop('disabled', false).html(originalText);
                
                var errorMsg = 'Terjadi kesalahan pada sistem.';
                
                // Cek jika error karena CSRF
                if (xhr.status === 403) {
                    errorMsg = 'Akses ditolak. Token keamanan tidak valid. Silakan refresh halaman dan coba lagi.';
                } else if (xhr.status === 500) {
                    errorMsg = 'Kesalahan server: ' + error;
                    try {
                        var resp = JSON.parse(xhr.responseText);
                        if (resp.message) {
                            errorMsg = resp.message;
                        }
                    } catch(e) {}
                } else if (xhr.status === 0) {
                    errorMsg = 'Koneksi terputus. Periksa koneksi internet Anda.';
                }
                
                console.error('AJAX Error:', xhr.responseText);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg,
                    confirmButtonColor: '#d33'
                });
            }
        });
    }
    
    // Auto-hide alert after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
