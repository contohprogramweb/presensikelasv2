<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Jadwal Pelajaran</h3>
                <button type="button" class="btn btn-primary" onclick="add_jadwal()">
                    <i class="fas fa-plus me-1"></i> Tambah Jadwal
                </button>
            </div>
        </div>
    </div>

    <!-- Alert -->
    <div id="alert_placeholder"></div>

    <!-- Form Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <i class="fas fa-filter me-1"></i> Filter Jadwal
        </div>
        <div class="card-body">
            <form id="form_filter" method="get" action="<?= site_url('admin/jadwal') ?>">
                <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="filter_kelas" class="form-label">Kelas</label>
                        <select class="form-select select2-filter" id="filter_kelas" name="id_kelas" style="width: 100%">
                            <option value="">-- Semua Kelas --</option>
                            <?php if(isset($kelas_list) && is_array($kelas_list)): ?>
                                <?php foreach($kelas_list as $k): ?>
                                    <option value="<?= $k->id ?>" <?= (isset($filter_id_kelas) && $filter_id_kelas == $k->id) ? 'selected' : '' ?>><?= $k->nama_kelas ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="filter_hari" class="form-label">Hari</label>
                        <select class="form-select" id="filter_hari" name="hari">
                            <option value="">-- Semua Hari --</option>
                            <option value="Senin" <?= (isset($filter_hari) && $filter_hari == 'Senin') ? 'selected' : '' ?>>Senin</option>
                            <option value="Selasa" <?= (isset($filter_hari) && $filter_hari == 'Selasa') ? 'selected' : '' ?>>Selasa</option>
                            <option value="Rabu" <?= (isset($filter_hari) && $filter_hari == 'Rabu') ? 'selected' : '' ?>>Rabu</option>
                            <option value="Kamis" <?= (isset($filter_hari) && $filter_hari == 'Kamis') ? 'selected' : '' ?>>Kamis</option>
                            <option value="Jumat" <?= (isset($filter_hari) && $filter_hari == 'Jumat') ? 'selected' : '' ?>>Jumat</option>
                            <option value="Sabtu" <?= (isset($filter_hari) && $filter_hari == 'Sabtu') ? 'selected' : '' ?>>Sabtu</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-info text-white">
                            <i class="fas fa-search me-1"></i> Tampilkan
                        </button>
                        <a href="<?= site_url('admin/jadwal') ?>" class="btn btn-secondary">
                            <i class="fas fa-redo me-1"></i> Reset
                        </a>
                        <?php if(isset($show_pdf_button) && $show_pdf_button): ?>
                        <button type="button" onclick="openPDF()" class="btn btn-danger">
                            <i class="fas fa-file-pdf me-1"></i> PDF
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script>
function openPDF() {
    var idKelas = $('#filter_kelas').val();
    var hari = $('#filter_hari').val();
    var params = [];
    
    if (idKelas) {
        params.push('id_kelas=' + encodeURIComponent(idKelas));
    }
    if (hari) {
        params.push('hari=' + encodeURIComponent(hari));
    }
    
    var queryString = params.join('&');
    var url = '<?= site_url('admin/jadwal/generate_pdf') ?>';
    
    if (queryString) {
        url += '?' + queryString;
    }
    
    window.open(url, '_blank');
}
</script>

    <!-- Tabel Jadwal -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-table me-1"></i> Daftar Jadwal Pelajaran - Tahun Ajaran <?= $tahun_ajaran->tahun_ajaran ?> (Semester <?= $tahun_ajaran->semester ?>)
            <?php if(isset($filter_info) && !empty($filter_info)): ?>
                <span class="float-end">(<?= $filter_info ?>)</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table_jadwal" class="table table-striped table-hover table-bordered" style="width: 100%">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Kelas</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Tahun Ajaran</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Jadwal -->
<div class="modal fade" id="modal_jadwal" tabindex="-1" aria-labelledby="modal_jadwal_label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal_jadwal_label">Form Jadwal Pelajaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_jadwal" method="post">
                <input type="hidden" name="id" id="id_jadwal">
                <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="id_kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="id_kelas" name="id_kelas" style="width: 100%">
                                <option value="">-- Pilih Kelas --</option>
                            </select>
                            <div class="invalid-feedback d-block" id="error_id_kelas"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="id_guru" class="form-label">Guru <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="id_guru" name="id_guru" style="width: 100%">
                                <option value="">-- Pilih Guru --</option>
                            </select>
                            <div class="invalid-feedback d-block" id="error_id_guru"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="id_mapel" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="id_mapel" name="id_mapel" style="width: 100%">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                            </select>
                            <div class="invalid-feedback d-block" id="error_id_mapel"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="hari" class="form-label">Hari <span class="text-danger">*</span></label>
                            <select class="form-select" id="hari" name="hari">
                                <option value="">-- Pilih Hari --</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                            <div class="invalid-feedback d-block" id="error_hari"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai">
                            <div class="invalid-feedback d-block" id="error_jam_mulai"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="jam_selesai" name="jam_selesai">
                            <div class="invalid-feedback d-block" id="error_jam_selesai"></div>
                        </div>
                        <div class="col-md-12">
                            <label for="ruangan" class="form-label">Ruangan</label>
                            <input type="text" class="form-control" id="ruangan" name="ruangan" placeholder="Contoh: Ruang 101">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn_simpan">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var table;
var current_kelas_id = null;
var skip_dropdown_reload = false;
var csrfName = '<?= $csrf_name ?>';
var csrfHash = '<?= $csrf_hash ?>';

$(document).ready(function() {
    // Initialize DataTables
    table = $('#table_jadwal').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        },
        ajax: {
            url: '<?= site_url('admin/jadwal/ajax_list') ?>',
            type: 'POST',
            data: function(d) {
                d[csrfName] = csrfHash;
                d.id_kelas = $('#filter_kelas').val();
                d.hari = $('#filter_hari').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', orderable: false, render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            {data: 'nama_kelas'},
            {data: 'hari'},
            {data: null, render: function(data, type, row) {
                return row.jam_mulai + ' - ' + row.jam_selesai;
            }},
            {data: 'nama_mapel'},
            {data: 'nama_guru'},
            {data: 'tahun_ajaran'},
            {data: 'action', orderable: false}
        ],
        order: [[2, 'asc'], [3, 'asc']],
        drawCallback: function() {
            // Refresh CSRF token
            $.getJSON('<?= site_url('security/get_csrf_hash') ?>', function(data) {
                csrfHash = data.csrf_hash;
                $('input[name="' + csrfName + '"]').val(data.csrf_hash);
            });
        }
    });

    // Handle edit button click
    $(document).on('click', '.edit-btn', function() {
        var encrypted_id = $(this).data('id');
        edit_jadwal(encrypted_id);
    });

    // Handle delete button click with SweetAlert
    $(document).on('click', '.delete-btn', function() {
        var encrypted_id = $(this).data('id');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Jadwal ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= site_url('admin/jadwal/ajax_delete') ?>/' + encodeURIComponent(encrypted_id),
                    type: 'POST',
                    data: {[csrfName]: csrfHash},
                    dataType: 'json',
                    success: function(response) {
                        if (response.csrf_hash) {
                            csrfHash = response.csrf_hash;
                        }
                        if (response.status) {
                            table.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus',
                                text: response.message,
                                timer: 2000
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete Error:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal menghapus: ' + error
                        });
                    }
                });
            }
        });
    });

    // Initialize Select2 without AJAX - will be populated manually
    $('.select2').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#modal_jadwal'),
        language: {
            noResults: function() {
                return "Tidak ada data ditemukan";
            },
            searching: function() {
                return "Sedang mencari...";
            }
        },
        placeholder: '-- Pilih --',
        allowClear: true
    });
    
    // Initialize Select2 for filter
    $('.select2-filter').select2({
        theme: 'bootstrap-5',
        language: {
            noResults: function() {
                return "Tidak ada data ditemukan";
            },
            searching: function() {
                return "Sedang mencari...";
            }
        },
        placeholder: '-- Pilih --',
        allowClear: true
    });

    // Load initial options for Select2 when page loads
    load_dropdown_options();
    
    // Reload dropdown options when modal is shown (but skip if editing)
    $('#modal_jadwal').on('shown.bs.modal', function() {
        if (!skip_dropdown_reload) {
            load_dropdown_options();
        }
        skip_dropdown_reload = false;
    });
});

function load_dropdown_options() {
    var promises = [];
    
    // Load kelas
    var kelasPromise = $.ajax({
        url: '<?= site_url('admin/jadwal/ajax_get_dropdown') ?>',
        data: {type: 'kelas'},
        dataType: 'json'
    }).done(function(response) {
        console.log('Kelas response:', response);
        var options = '<option value="">-- Pilih Kelas --</option>';
        var results = response.results || response;
        if (results && Array.isArray(results) && results.length > 0) {
            $.each(results, function(i, item) {
                options += '<option value="' + item.id + '">' + item.text + '</option>';
            });
        }
        $('#id_kelas').html(options).trigger('change');
    }).fail(function(xhr, status, error) {
        console.error('Error loading kelas:', status, error, xhr.responseText);
        $('#id_kelas').html('<option value="">-- Pilih Kelas --</option>').trigger('change');
    });
    promises.push(kelasPromise);

    // Load guru
    var guruPromise = $.ajax({
        url: '<?= site_url('admin/jadwal/ajax_get_dropdown') ?>',
        data: {type: 'guru'},
        dataType: 'json'
    }).done(function(response) {
        console.log('Guru response:', response);
        var options = '<option value="">-- Pilih Guru --</option>';
        var results = response.results || response;
        if (results && Array.isArray(results) && results.length > 0) {
            $.each(results, function(i, item) {
                options += '<option value="' + item.id + '">' + item.text + '</option>';
            });
        }
        $('#id_guru').html(options).trigger('change');
    }).fail(function(xhr, status, error) {
        console.error('Error loading guru:', status, error, xhr.responseText);
        $('#id_guru').html('<option value="">-- Pilih Guru --</option>').trigger('change');
    });
    promises.push(guruPromise);

    // Load mapel
    var mapelPromise = $.ajax({
        url: '<?= site_url('admin/jadwal/ajax_get_dropdown') ?>',
        data: {type: 'mapel'},
        dataType: 'json'
    }).done(function(response) {
        console.log('Mapel response:', response);
        var options = '<option value="">-- Pilih Mata Pelajaran --</option>';
        var results = response.results || response;
        if (results && Array.isArray(results) && results.length > 0) {
            $.each(results, function(i, item) {
                options += '<option value="' + item.id + '">' + item.text + '</option>';
            });
        }
        $('#id_mapel').html(options).trigger('change');
    }).fail(function(xhr, status, error) {
        console.error('Error loading mapel:', status, error, xhr.responseText);
        $('#id_mapel').html('<option value="">-- Pilih Mata Pelajaran --</option>').trigger('change');
    });
    promises.push(mapelPromise);
    
    return $.when.apply($, promises);
}

function add_jadwal() {
    $('#form_jadwal')[0].reset();
    $('#id_jadwal').val('');
    $('.invalid-feedback').text('').hide();
    $('.is-invalid').removeClass('is-invalid');
    
    // Reset Select2 values
    $('#id_kelas').val(null).trigger('change');
    $('#id_guru').val(null).trigger('change');
    $('#id_mapel').val(null).trigger('change');
    
    // Reload dropdown options before showing modal
    load_dropdown_options();
    
    $('#modal_jadwal_label').text('Tambah Jadwal Pelajaran');
    $('#modal_jadwal').modal('show');
}

function edit_jadwal(encrypted_id) {
    // Set flag to skip dropdown reload when modal is shown
    skip_dropdown_reload = true;
    
    // First, reload dropdown options and wait for them to complete
    load_dropdown_options().done(function() {
        $.ajax({
            url: '<?= site_url('admin/jadwal/ajax_edit') ?>/' + encrypted_id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    var data = response.data;
                    $('#id_jadwal').val(encrypted_id);
                    
                    // Clear errors first
                    $('.invalid-feedback').text('').hide();
                    $('.is-invalid').removeClass('is-invalid');
                    
                    // Show modal first
                    $('#modal_jadwal_label').text('Edit Jadwal Pelajaran');
                    $('#modal_jadwal').modal('show');
                    
                    // Wait for modal to be fully shown before setting values
                    setTimeout(function() {
                        // Set Select2 values - use val() with trigger('change')
                        $('#id_kelas').val(data.id_kelas).trigger('change.select2');
                        $('#id_guru').val(data.id_guru).trigger('change.select2');
                        $('#id_mapel').val(data.id_mapel).trigger('change.select2');
                        
                        // Set other field values
                        $('#hari').val(data.hari);
                        $('#jam_mulai').val(data.jam_mulai);
                        $('#jam_selesai').val(data.jam_selesai);
                        $('#ruangan').val(data.ruangan);
                    }, 100);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message || 'Gagal mengambil data'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Edit error:', status, error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat mengambil data'
                });
            }
        });
    }).fail(function() {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Gagal memuat data dropdown'
        });
    });
}

$('#form_jadwal').submit(function(e) {
    e.preventDefault();
    
    var url = $('#id_jadwal').val() ? '<?= site_url('admin/jadwal/ajax_update') ?>' : '<?= site_url('admin/jadwal/ajax_add') ?>';
    
    // Get current CSRF token
    var csrfData = {};
    csrfData[csrfName] = csrfHash;
    
    $.ajax({
        url: url,
        type: 'POST',
        data: $(this).serialize() + '&' + $.param(csrfData),
        dataType: 'json',
        success: function(response) {
            console.log('Response:', response);
            if (response.status) {
                $('#modal_jadwal').modal('hide');
                table.ajax.reload(null, false);
                
                // Refresh CSRF token after successful operation
                $.getJSON('<?= site_url('security/get_csrf_hash') ?>', function(data) {
                    csrfHash = data.csrf_hash;
                });
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    timer: 2000
                });
            } else {
                // Update CSRF token
                if (response.csrf_hash) {
                    csrfHash = response.csrf_hash;
                }
                
                if (response.error) {
                    // Show validation errors for specific fields
                    $('.invalid-feedback').text('');
                    $('.is-invalid').removeClass('is-invalid');
                    $.each(response.error, function(field, msg) {
                        $('#' + field).addClass('is-invalid');
                        $('#error_' + field).text(msg).show();
                    });
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.message || 'Validasi gagal'
                });
            }
        },
        error: function(xhr) {
            console.log('Error XHR:', xhr);
            if (xhr.status === 400) {
                var response = xhr.responseJSON;
                if (response && response.error) {
                    // Show validation errors for specific fields
                    $('.invalid-feedback').text('');
                    $('.is-invalid').removeClass('is-invalid');
                    $.each(response.error, function(field, msg) {
                        $('#' + field).addClass('is-invalid');
                        $('#error_' + field).text(msg).show();
                    });
                } else if (response && response.message) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            } else {
                var errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                if (xhr.status === 403) {
                    errorMsg += ' Error 403: Forbidden - Mungkin masalah CSRF token.';
                } else if (xhr.status === 500) {
                    errorMsg += ' Error 500: Server error.';
                } else if (xhr.responseText) {
                    errorMsg += ' Detail: ' + xhr.responseText.substring(0, 200);
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });
            }
        }
    });
});

function showAlert(type, message) {
    var alertClass = type === 'success' ? 'alert-success' : (type === 'warning' ? 'alert-warning' : 'alert-danger');
    var icon = type === 'success' ? 'fa-check-circle' : (type === 'warning' ? 'fa-exclamation-triangle' : 'fa-times-circle');
    
    var html = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
               '<i class="fas ' + icon + ' me-2"></i>' + message +
               '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
               '</div>';
    
    $('#alert_placeholder').html(html);
    
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);
}
</script>