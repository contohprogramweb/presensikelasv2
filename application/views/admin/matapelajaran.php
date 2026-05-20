<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Mata Pelajaran</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMapel" onclick="resetForm()">
            <i class="fas fa-plus"></i> Tambah Mata Pelajaran
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tableMapel" class="table table-bordered table-striped" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Nama Mata Pelajaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

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
            {'orderable': false, 'targets': 1}
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
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            }
        });
    });

    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get('<?= site_url('admin/matapelajaran/ajax_edit') ?>/' + id, function(response) {
            if (response.status) {
                $('#modalTitle').text('Edit Mata Pelajaran');
                $('#id').val(response.data.id);
                $('#nama_mapel').val(response.data.nama_mapel);
                $('#modalMapel').modal('show');
            }
        });
    });

    $(document).on('click', '.delete-btn', function() {
        if (confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')) {
            var id = $(this).data('id');
            $.post('<?= site_url('admin/matapelajaran/ajax_delete') ?>', {
                id: id,
                '<?= $csrf_name ?>': '<?= $csrf_hash ?>'
            }, function(response) {
                if (response.status) {
                    table.ajax.reload();
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            });
        }
    });
});

function resetForm() {
    $('#formMapel')[0].reset();
    $('#id').val('');
    $('#modalTitle').text('Tambah Mata Pelajaran');
}
</script>
