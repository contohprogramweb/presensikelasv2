<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Kelas</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKelas" onclick="resetForm()">
            <i class="fas fa-plus"></i> Tambah Kelas
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tableKelas" class="table table-bordered table-striped" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Nama Kelas</th>
                        <th>Wali Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add/Edit -->
<div class="modal fade" id="modalKelas" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formKelas">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">
                    
                    <div class="mb-3">
                        <label for="nama_kelas" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_wali_kelas" class="form-label">Wali Kelas</label>
                        <select class="form-select" id="id_wali_kelas" name="id_wali_kelas">
                            <option value="">-- Pilih Wali Kelas --</option>
                        </select>
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
    // Load DataTables
    var table = $('#tableKelas').DataTable({
        'processing': true,
        'serverSide': false,
        'ajax': {
            'url': '<?= site_url('admin/kelas/ajax_list') ?>',
            'type': 'POST'
        },
        'pageLength': 10,
        'language': {
            'url': '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        'columnDefs': [
            {'orderable': false, 'targets': 3}
        ]
    });

    // Load guru untuk select wali kelas
    $.get('<?= site_url('admin/guru/ajax_list_guru_select') ?>', function(data) {
        var options = '<option value="">-- Pilih Wali Kelas --</option>';
        data.forEach(function(guru) {
            options += '<option value="' + guru.id + '">' + guru.nama_lengkap + '</option>';
        });
        $('#id_wali_kelas').html(options);
    });

    // Submit form
    $('#formKelas').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var url = $('#id').val() ? '<?= site_url('admin/kelas/ajax_update') ?>' : '<?= site_url('admin/kelas/ajax_add') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    $('#modalKelas').modal('hide');
                    table.ajax.reload();
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            }
        });
    });

    // Edit button
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get('<?= site_url('admin/kelas/ajax_edit') ?>/' + id, function(response) {
            if (response.status) {
                $('#modalTitle').text('Edit Kelas');
                $('#id').val(response.data.id);
                $('#nama_kelas').val(response.data.nama_kelas);
                $('#id_wali_kelas').val(response.data.id_wali_kelas);
                $('#modalKelas').modal('show');
            }
        });
    });

    // Delete button
    $(document).on('click', '.delete-btn', function() {
        if (confirm('Apakah Anda yakin ingin menghapus kelas ini?')) {
            var id = $(this).data('id');
            $.post('<?= site_url('admin/kelas/ajax_delete') ?>', {
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
    $('#formKelas')[0].reset();
    $('#id').val('');
    $('#modalTitle').text('Tambah Kelas');
}
</script>
