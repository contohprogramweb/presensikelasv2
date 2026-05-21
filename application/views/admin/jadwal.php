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

    <!-- Tabel Jadwal -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-table me-1"></i> Daftar Jadwal Pelajaran - Tahun Ajaran <?= $tahun_ajaran->tahun_ajaran ?> (Semester <?= $tahun_ajaran->semester ?>)
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
                            <div class="invalid-feedback" id="error_id_kelas"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="id_guru" class="form-label">Guru <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="id_guru" name="id_guru" style="width: 100%">
                                <option value="">-- Pilih Guru --</option>
                            </select>
                            <div class="invalid-feedback" id="error_id_guru"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="id_mapel" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="id_mapel" name="id_mapel" style="width: 100%">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                            </select>
                            <div class="invalid-feedback" id="error_id_mapel"></div>
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
                            <div class="invalid-feedback" id="error_hari"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai">
                            <div class="invalid-feedback" id="error_jam_mulai"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="jam_selesai" name="jam_selesai">
                            <div class="invalid-feedback" id="error_jam_selesai"></div>
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

<!-- Modal Delete -->
<div class="modal fade" id="modal_delete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus jadwal ini?</p>
                <p class="text-danger small"><strong>Peringatan:</strong> Data yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn_confirm_delete">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
var table;
var delete_id = null;
var current_kelas_id = null;

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
                d.<?= $csrf_name ?> = '<?= $csrf_hash ?>';
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
                $('input[name="<?= $csrf_name ?>"]').val(data.csrf_hash);
            });
        }
    });

    // Initialize Select2 with AJAX
    $('.select2').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#modal_jadwal'),
        ajax: {
            url: '<?= site_url('admin/jadwal/ajax_get_dropdown') ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    type: params.element[0].id.replace('id_', ''),
                    q: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.results || data
                };
            },
            cache: true
        },
        minimumInputLength: 0,
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
    
    // Reload dropdown options when modal is shown
    $('#modal_jadwal').on('shown.bs.modal', function() {
        load_dropdown_options();
    });
});

function load_dropdown_options() {
    // Load kelas
    $.ajax({
        url: '<?= site_url('admin/jadwal/ajax_get_dropdown') ?>',
        data: {type: 'kelas'},
        dataType: 'json',
        success: function(response) {
            var options = '<option value="">-- Pilih Kelas --</option>';
            var results = response.results || response;
            if (results && results.length > 0) {
                $.each(results, function(i, item) {
                    options += '<option value="' + item.id + '">' + item.text + '</option>';
                });
            }
            $('#id_kelas').html(options).trigger('change.select2');
        },
        error: function(xhr, status, error) {
            console.error('Error loading kelas:', error);
            $('#id_kelas').html('<option value="">-- Pilih Kelas --</option>').trigger('change.select2');
        }
    });

    // Load guru
    $.ajax({
        url: '<?= site_url('admin/jadwal/ajax_get_dropdown') ?>',
        data: {type: 'guru'},
        dataType: 'json',
        success: function(response) {
            var options = '<option value="">-- Pilih Guru --</option>';
            var results = response.results || response;
            if (results && Array.isArray(results) && results.length > 0) {
                $.each(results, function(i, item) {
                    options += '<option value="' + item.id + '">' + item.text + '</option>';
                });
            }
            $('#id_guru').html(options).trigger('change.select2');
        },
        error: function(xhr, status, error) {
            console.error('Error loading guru:', error);
            $('#id_guru').html('<option value="">-- Pilih Guru --</option>').trigger('change.select2');
        }
    });

    // Load mapel
    $.ajax({
        url: '<?= site_url('admin/jadwal/ajax_get_dropdown') ?>',
        data: {type: 'mapel'},
        dataType: 'json',
        success: function(response) {
            var options = '<option value="">-- Pilih Mata Pelajaran --</option>';
            var results = response.results || response;
            if (results && Array.isArray(results) && results.length > 0) {
                $.each(results, function(i, item) {
                    options += '<option value="' + item.id + '">' + item.text + '</option>';
                });
            }
            $('#id_mapel').html(options).trigger('change.select2');
        },
        error: function(xhr, status, error) {
            console.error('Error loading mapel:', error);
            $('#id_mapel').html('<option value="">-- Pilih Mata Pelajaran --</option>').trigger('change.select2');
        }
    });
}

function add_jadwal() {
    $('#form_jadwal')[0].reset();
    $('#id_jadwal').val('');
    $('.invalid-feedback').hide();
    $('.is-invalid').removeClass('is-invalid');
    
    // Reload dropdown options before showing modal
    load_dropdown_options();
    
    $('#modal_jadwal_label').text('Tambah Jadwal Pelajaran');
    $('#modal_jadwal').modal('show');
}

function edit_jadwal(encrypted_id) {
    // First, reload dropdown options to ensure they're available
    load_dropdown_options();
    
    $.ajax({
        url: '<?= site_url('admin/jadwal/ajax_edit') ?>/' + encrypted_id,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                var data = response.data;
                $('#id_jadwal').val(encrypted_id);
                
                // Set values and trigger change event for Select2
                // Wait a bit for the dropdown options to load
                setTimeout(function() {
                    $('#id_kelas').val(data.id_kelas).trigger('change');
                    $('#id_guru').val(data.id_guru).trigger('change');
                    $('#id_mapel').val(data.id_mapel).trigger('change');
                    $('#hari').val(data.hari);
                    $('#jam_mulai').val(data.jam_mulai);
                    $('#jam_selesai').val(data.jam_selesai);
                    $('#ruangan').val(data.ruangan);
                    
                    $('.invalid-feedback').hide();
                    $('.is-invalid').removeClass('is-invalid');
                    $('#modal_jadwal_label').text('Edit Jadwal Pelajaran');
                    $('#modal_jadwal').modal('show');
                }, 300);
            } else {
                showAlert('danger', response.message || 'Gagal mengambil data');
            }
        },
        error: function() {
            showAlert('danger', 'Terjadi kesalahan saat mengambil data');
        }
    });
}

function delete_jadwal(encrypted_id) {
    delete_id = encrypted_id;
    $('#modal_delete').modal('show');
}

$('#btn_confirm_delete').click(function() {
    if (!delete_id) return;
    
    $.ajax({
        url: '<?= site_url('admin/jadwal/ajax_delete') ?>/' + delete_id,
        type: 'POST',
        data: {<?= $csrf_name ?>: '<?= $csrf_hash ?>'},
        dataType: 'json',
        success: function(response) {
            $('#modal_delete').modal('hide');
            if (response.status) {
                showAlert('success', response.message);
                table.ajax.reload(null, false);
            } else {
                showAlert('danger', response.message);
            }
            delete_id = null;
        },
        error: function() {
            $('#modal_delete').modal('hide');
            showAlert('danger', 'Terjadi kesalahan saat menghapus data');
            delete_id = null;
        }
    });
});

$('#form_jadwal').submit(function(e) {
    e.preventDefault();
    
    var url = $('#id_jadwal').val() ? '<?= site_url('admin/jadwal/ajax_update') ?>' : '<?= site_url('admin/jadwal/ajax_add') ?>';
    
    $.ajax({
        url: url,
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                $('#modal_jadwal').modal('hide');
                showAlert('success', response.message);
                table.ajax.reload(null, false);
            } else {
                if (response.error) {
                    // Show validation errors
                    $.each(response.error, function(i, msg) {
                        showAlert('warning', msg);
                    });
                } else {
                    showAlert('danger', response.message || 'Gagal menyimpan data');
                }
            }
        },
        error: function(xhr) {
            if (xhr.status === 400) {
                var response = xhr.responseJSON;
                if (response && response.message) {
                    showAlert('danger', response.message);
                }
            } else {
                showAlert('danger', 'Terjadi kesalahan saat menyimpan data');
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
