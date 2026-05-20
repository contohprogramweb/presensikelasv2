<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar3"></i> <?= $page_title ?></h5>
                    <button class="btn btn-light btn-sm" onclick="add_tahunajaran()">
                        <i class="bi bi-plus-lg"></i> Tambah Tahun Ajaran
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_tahunajaran" class="table table-striped table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Semester</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
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
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modal_form" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalLabel">Form Tahun Ajaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_tahunajaran" method="post">
                <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>" id="csrf_token">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="tahun_ajaran" id="tahun_ajaran" placeholder="Contoh: 2025/2026" required>
                        <small class="text-danger error-text" id="error_tahun_ajaran"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester <span class="text-danger">*</span></label>
                        <select class="form-select" name="semester" id="semester" required>
                            <option value="">Pilih Semester</option>
                            <option value="1">Ganjil</option>
                            <option value="2">Genap</option>
                        </select>
                        <small class="text-danger error-text" id="error_semester"></small>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status_aktif" id="status_aktif" value="1">
                            <label class="form-check-label" for="status_aktif">
                                Set sebagai Tahun Ajaran Aktif
                            </label>
                        </div>
                        <small class="text-muted">Jika dicentang, tahun ajaran lain akan menjadi tidak aktif.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn_save">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var table;
var save_method;

$(document).ready(function() {
    // DataTables
    table = $('#table_tahunajaran').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        },
        ajax: {
            url: '<?= site_url('admin/tahunajaran/ajax_list') ?>',
            type: 'POST',
            data: function(d) {
                d['<?= $csrf_name ?>'] = $('input[name="<?= $csrf_name ?>"]').val();
            },
            dataFilter: function(data) {
                var json = jQuery.parseJSON(data);
                // Update CSRF token
                $('#csrf_token').val(json.csrf_hash);
                return JSON.stringify(json);
            }
        },
        columns: [
            {data: null, orderable: false},
            {data: 'tahun_ajaran'},
            {data: 'semester'},
            {data: 'status_aktif', orderable: false},
            {data: 'tanggal_dibuat'},
            {data: 'action_btn', orderable: false}
        ],
        order: [[0, 'desc']]
    });

    // Form submit
    $('#form_tahunajaran').on('submit', function(e) {
        e.preventDefault();
        
        $('#btn_save').text('Menyimpan...').prop('disabled', true);
        $('.error-text').text('');
        
        var url = save_method === 'add' 
            ? '<?= site_url('admin/tahunajaran/ajax_add') ?>' 
            : '<?= site_url('admin/tahunajaran/ajax_update') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    $('#modal_form').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 2000
                    });
                    table.ajax.reload(null, false);
                    // Update CSRF
                    $('input[name="<?= $csrf_name ?>"]').val(response.csrf_hash);
                } else {
                    if (response.error) {
                        $.each(response.error, function(key, val) {
                            $('#error_' + key).text(val);
                        });
                    }
                    $('#btn_save').text('Simpan').prop('disabled', false);
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan pada sistem'
                });
                $('#btn_save').text('Simpan').prop('disabled', false);
            }
        });
    });
});

function add_tahunajaran() {
    save_method = 'add';
    $('#form_tahunajaran')[0].reset();
    $('#id').val('');
    $('#modalLabel').text('Tambah Tahun Ajaran');
    $('.error-text').text('');
    $('#modal_form').modal('show');
}

function edit_tahunajaran(id) {
    save_method = 'edit';
    $('.error-text').text('');
    
    $.ajax({
        url: '<?= site_url('admin/tahunajaran/ajax_edit') ?>/' + id,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                $('#id').val(response.data.id);
                $('#tahun_ajaran').val(response.data.tahun_ajaran);
                $('#semester').val(response.data.semester);
                $('#status_aktif').prop('checked', response.data.status_aktif == 1);
                $('#modalLabel').text('Edit Tahun Ajaran');
                $('#modal_form').modal('show');
                // Update CSRF
                $('input[name="<?= $csrf_name ?>"]').val(response.csrf_hash);
            }
        }
    });
}

function delete_tahunajaran(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Tahun ajaran akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= site_url('admin/tahunajaran/ajax_delete') ?>/' + id,
                type: 'POST',
                data: {'<?= $csrf_name ?>': $('input[name="<?= $csrf_name ?>"]').val()},
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus',
                            text: response.message,
                            timer: 2000
                        });
                        table.ajax.reload(null, false);
                        $('input[name="<?= $csrf_name ?>"]').val(response.csrf_hash);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                }
            });
        }
    });
}
</script>
