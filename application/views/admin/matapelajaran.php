<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="fas fa-book me-2"></i>Kelola Mata Pelajaran</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMapel" onclick="resetForm()">
                    <i class="fas fa-plus me-1"></i> Tambah Mata Pelajaran
                </button>
            </div>
        </div>
    </div>

    <!-- Alert -->
    <div id="alert_placeholder"></div>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Daftar Mata Pelajaran
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableMapel" class="table table-bordered table-striped table-hover" style="width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Mata Pelajaran</th>
                            <th>Nama Mata Pelajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Modal Add/Edit -->
<div class="modal fade" id="modalMapel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formMapel">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Mata Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">
                    
                    <div class="mb-3">
                        <label for="kode_mapel" class="form-label">Kode Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode_mapel" name="kode_mapel" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nama_mapel" class="form-label">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_mapel" name="nama_mapel" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var table = $('#tableMapel').DataTable({
        'processing': true,
        'serverSide': false,
        'ajax': {
            'url': '<?= site_url('admin/matapelajaran/ajax_list') ?>',
            'type': 'POST'
        },
        'pageLength': 10,
        'language': {
            'url': '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        'columnDefs': [
            {
                'targets': 0,
                'orderable': false,
                'render': function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {'orderable': false, 'targets': 3}
        ]
    });

    $('#formMapel').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var url = $('#id').val() ? '<?= site_url('admin/matapelajaran/ajax_update') ?>' : '<?= site_url('admin/matapelajaran/ajax_add') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    $('#modalMapel').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            }
        });
    });

    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?= site_url('admin/matapelajaran/ajax_edit') ?>',
            type: 'POST',
            data: { 
                id: id,
                '<?= $csrf_name ?>': '<?= $csrf_hash ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    $('#modalTitle').text('Edit Mata Pelajaran');
                    $('#id').val(response.data.id);
                    $('#kode_mapel').val(response.data.kode_mapel);
                    $('#nama_mapel').val(response.data.nama_mapel);
                    $('#modalMapel').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            }
        });
    });

    $(document).on('click', '.delete-btn', function() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data mata pelajaran ini akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                var id = $(this).data('id');
                $.ajax({
                    url: '<?= site_url('admin/matapelajaran/ajax_delete') ?>',
                    type: 'POST',
                    data: {
                        id: id,
                        '<?= $csrf_name ?>': '<?= $csrf_hash ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Update CSRF token for next request
                        if (response.csrf_name && response.csrf_hash) {
                            $('[name="<?= $csrf_name ?>"]').val(response.csrf_hash);
                        }
                        
                        if (response.status) {
                            table.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message || 'Terjadi kesalahan saat menghapus data'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete error:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan pada server: ' + error
                        });
                    }
                });
            }
        });
    });
});

function resetForm() {
    $('#formMapel')[0].reset();
    $('#id').val('');
    $('#modalTitle').text('Tambah Mata Pelajaran');
}
</script>
