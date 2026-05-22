<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="fas fa-chalkboard me-2"></i>Kelola Kelas</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKelas" onclick="resetForm()">
                    <i class="fas fa-plus me-1"></i> Tambah Kelas
                </button>
            </div>
        </div>
    </div>

    <!-- Alert -->
    <div id="alert_placeholder"></div>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Daftar Kelas
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableKelas" class="table table-bordered table-striped table-hover" style="width: 100%;">
                    <thead class="table-light">
                        <tr>
							<th>No</th>
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
                    <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>" id="csrf_token">

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
                    <button type="submit" class="btn btn-primary" id="btn_save">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var csrf_name = '<?= $csrf_name ?? '' ?>';
var csrf_hash = '<?= $csrf_hash ?? '' ?>';

$(document).ready(function() {
    // FIX: ajax type GET agar tidak trigger CSRF, dan draw dikirim otomatis oleh DataTables
    var table = $('#tableKelas').DataTable({
        'processing': true,
        'serverSide': false,
        'ajax': {
            'url': '<?= site_url('admin/kelas/ajax_list') ?>',
            'type': 'POST',
            'data': function(d) {
                d[csrf_name] = csrf_hash;
            },
            'dataFilter': function(data) {
                try {
                    var json = jQuery.parseJSON(data);
                    if (json.csrf_hash) {
                        csrf_hash = json.csrf_hash;
                        $('#csrf_token').val(json.csrf_hash);
                        $('input[name="' + csrf_name + '"]').val(json.csrf_hash);
                    }
                    return JSON.stringify(json);
                } catch(e) {
                    console.error('ajax_list parse error:', e.message, data);
                    return JSON.stringify({draw:1, recordsTotal:0, recordsFiltered:0, data:[]});
                }
            },
            'error': function(xhr) {
                console.error('Ajax error:', xhr.status, xhr.responseText);
                Swal.fire({icon: 'error', title: 'Error', text: 'Gagal memuat data kelas (HTTP ' + xhr.status + ')'});
            }
        },
        'pageLength': 10,
        'language': {
            'url': '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
       'columnDefs': [
            {
                'orderable': false,
                'targets': 4,
                'render': function(data, type, row, meta) {
                    return data;
                }
            },
            {
                'targets': 0,
                'render': function(data, type, row, meta) {
                    return meta.row + 1;
                }
            }
        ]
    });

   
	
	// Ganti URL menjadi endpoint yang baru
	$.get('<?= site_url('admin/kelas/ajax_get_guru_list') ?>', function(data) {
		var options = '<option value="">-- Pilih Wali Kelas --</option>';
		$.each(data, function(i, guru) {
			options += '<option value="' + guru.id + '">' + guru.nama_lengkap + '</option>';
		});
		$('#id_wali_kelas').html(options);
	});



    // Submit form
    $('#formKelas').on('submit', function(e) {
        e.preventDefault();

        $('#btn_save').text('Menyimpan...').prop('disabled', true);

        var url = $('#id').val()
            ? '<?= site_url('admin/kelas/ajax_update') ?>'
            : '<?= site_url('admin/kelas/ajax_add') ?>';

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.csrf_hash) {
                    csrf_hash = response.csrf_hash;
                    $('#csrf_token').val(response.csrf_hash);
                    $('input[name="' + csrf_name + '"]').val(response.csrf_hash);
                }
                $('#btn_save').text('Simpan').prop('disabled', false);
                if (response.status) {
                    $('#modalKelas').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({icon: 'success', title: 'Berhasil', text: response.message, timer: 2000});
                } else {
                    Swal.fire({icon: 'error', title: 'Gagal', text: response.message});
                }
            },
            error: function(xhr) {
                $('#btn_save').text('Simpan').prop('disabled', false);
                Swal.fire({icon: 'error', title: 'Error', text: 'Terjadi kesalahan pada sistem'});
            }
        });
    });

    // Edit button — FIX: pakai encrypted_id dari response, bukan raw id
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get('<?= site_url('admin/kelas/ajax_edit') ?>/' + id, function(response) {
            if (response.status) {
                $('#modalTitle').text('Edit Kelas');
                $('#id').val(response.data.encrypted_id);
                $('#nama_kelas').val(response.data.nama_kelas);
                $('#id_wali_kelas').val(response.data.id_wali_kelas);
                if (response.csrf_hash) {
                    csrf_hash = response.csrf_hash;
                    $('#csrf_token').val(response.csrf_hash);
                    $('input[name="' + csrf_name + '"]').val(response.csrf_hash);
                }
                $('#modalKelas').modal('show');
            }
        });
    });

    // Delete button
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Kelas akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                var postData = {id: id};
                postData[csrf_name] = csrf_hash;
                $.ajax({
                    url: '<?= site_url('admin/kelas/ajax_delete') ?>/' + id,
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.csrf_hash) {
                            csrf_hash = response.csrf_hash;
                            $('#csrf_token').val(response.csrf_hash);
                            $('input[name="' + csrf_name + '"]').val(response.csrf_hash);
                        }
                        if (response.status) {
                            table.ajax.reload(null, false);
                            Swal.fire({icon: 'success', title: 'Terhapus', text: response.message, timer: 2000});
                        } else {
                            Swal.fire({icon: 'error', title: 'Gagal', text: response.message});
                        }
                    }
                });
            }
        });
    });
});

function resetForm() {
    $('#formKelas')[0].reset();
    $('#id').val('');
    $('#modalTitle').text('Tambah Kelas');
}
</script>