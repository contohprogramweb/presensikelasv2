<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Kelola Guru</h3>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalGuru" onclick="resetForm()">
                        <i class="fas fa-plus me-1"></i> Tambah Guru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert -->
    <div id="alert_placeholder"></div>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Daftar Guru
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableGuru" class="table table-bordered table-striped table-hover" style="width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>L/P</th>
                            <th>No HP</th>
                            <th>Username</th>
                            <th>Status</th>
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
<div class="modal fade" id="modalGuru" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formGuru">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nip" name="nip" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="2"></textarea>
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
    // Get CSRF token from meta tag or hidden input
    var csrfName = '<?= $csrf_name ?>';
    var csrfHash = '<?= $csrf_hash ?>';
    
    var table = $('#tableGuru').DataTable({
        'processing': true,
        'serverSide': false,
        'ajax': {
            'url': '<?= site_url('admin/guru/ajax_list') ?>',
            'type': 'POST',
            'data': function(d) {
                d[csrfName] = csrfHash;
            },
            'dataFilter': function(data){
                try {
                    var json = jQuery.parseJSON(data);
                    // Update CSRF token for next request
                    if(json.csrf_hash) {
                        csrfHash = json.csrf_hash;
                    }
                    return data;
                } catch(e) {
                    console.error('Error parsing JSON:', data);
                    console.error('Error:', e);
                    alert('Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
                    return data;
                }
            },
            'error': function(xhr, error, thrown) {
                console.error('AJAX Error:', error);
                console.error('Status:', xhr.status);
                console.error('Response Text:', xhr.responseText);
                
                var errorMsg = 'Gagal memuat data.';
                if (xhr.status === 403) {
                    errorMsg += ' Error 403: Forbidden - Mungkin masalah CSRF token.';
                } else if (xhr.status === 500) {
                    errorMsg += ' Error 500: Server error.';
                } else if (xhr.responseText) {
                    errorMsg += ' Detail: ' + xhr.responseText.substring(0, 200);
                }
                
                alert(errorMsg);
            }
        },
        'pageLength': 10,
        'language': {
            'url': '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        'columnDefs': [
            {'orderable': false, 'targets': 7}
        ],
        'createdRow': function(row, data, dataIndex) {
            $('td:eq(0)', row).html(dataIndex + 1);
        }
    });

    $('#formGuru').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        // Add current CSRF token
        formData.append(csrfName, csrfHash);
        
        var url = $('#id').val() ? '<?= site_url('admin/guru/ajax_update') ?>' : '<?= site_url('admin/guru/ajax_add') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    $('#modalGuru').modal('hide');
                    table.ajax.reload(null, false);
                    // Update CSRF token
                    csrfHash = response.csrf_hash;
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 2000
                    });
                } else {
                    // Update CSRF token even on error
                    csrfHash = response.csrf_hash;
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Submit Error:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan pada sistem'
                });
            }
        });
    });

    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get('<?= site_url('admin/guru/ajax_edit') ?>/' + encodeURIComponent(id), function(response) {
            if (response.status) {
                $('#modalTitle').text('Edit Guru');
                $('#id').val(id); // Gunakan ID terenkripsi langsung
                $('#nip').val(response.data.nip);
                $('#nama').val(response.data.nama_lengkap);
                $('#jenis_kelamin').val(response.data.jenis_kelamin);
                $('#email').val(response.data.email || '');
                $('#no_hp').val(response.data.no_hp || '');
                $('#alamat').val(response.data.alamat || '');
                $('#modalGuru').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.message
                });
            }
        }, 'json').fail(function(xhr, status, error) {
            console.error('Edit Error:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal mengambil data guru'
            });
        });
    });

    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data guru akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= site_url('admin/guru/ajax_delete') ?>/' + encodeURIComponent(id),
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
});

function resetForm() {
    $('#formGuru')[0].reset();
    $('#id').val('');
    $('#modalTitle').text('Tambah Guru');
}
</script>
