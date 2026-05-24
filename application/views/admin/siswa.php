<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Kelola Siswa</h3>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSiswa" onclick="resetForm()">
                        <i class="fas fa-plus me-1"></i> Tambah Siswa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert -->
    <div id="alert_placeholder"></div>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Daftar Siswa
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableSiswa" class="table table-bordered table-striped table-hover" style="width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>L/P</th>
                            <th>Kelas</th>
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
<div class="modal fade" id="modalSiswa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formSiswa">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nis" name="nis" required>
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
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_kelas" class="form-label">Kelas</label>
                        <select class="form-select" id="id_kelas" name="id_kelas">
                            <option value="">-- Pilih Kelas --</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="2"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_ortu" class="form-label">Nama Orang Tua</label>
                            <input type="text" class="form-control" id="nama_ortu" name="nama_ortu">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_hp_orang_tua" class="form-label">No HP Orang Tua</label>
                            <input type="text" class="form-control" id="no_hp_orang_tua" name="no_hp_orang_tua">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label">No HP Siswa</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger" id="password_required">*</span></label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-muted" id="password_hint">Kosongkan jika tidak ingin mengubah password</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Siswa <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
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
    // Get CSRF token from meta tag or hidden input
    var csrfName = '<?= $csrf_name ?>';
    var csrfHash = '<?= $csrf_hash ?>';
    
    var table = $('#tableSiswa').DataTable({
        'processing': true,
        'serverSide': false,
        'ajax': {
            'url': '<?= site_url('admin/siswa/ajax_list') ?>',
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
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });
            }
        },
        'pageLength': 10,
        'language': {
            'url': '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        'columnDefs': [
            {'orderable': false, 'targets': 7}
        ]
    });

    // Load kelas select - call after page load
    function loadKelasSelect() {
        $.ajax({
            url: '<?= site_url('admin/siswa/get_kelas_select') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var options = '<option value="">-- Pilih Kelas --</option>';
                if (data && Array.isArray(data)) {
                    data.forEach(function(kelas) {
                        options += '<option value="' + kelas.id + '">' + kelas.nama_kelas + '</option>';
                    });
                }
                $('#id_kelas').html(options);
                console.log('Kelas loaded:', data);
            },
            error: function(xhr, status, error) {
                console.error('Error loading kelas:', error);
                console.error('Status:', xhr.status);
                console.error('Response:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal memuat data kelas: ' + xhr.status + ' - ' + xhr.responseText.substring(0, 100)
                });
            }
        });
    }
    
    // Load kelas on page init
    loadKelasSelect();
    
    // Reset form function
    window.resetForm = function() {
        $('#formSiswa')[0].reset();
        $('#id').val('');
        $('#modalTitle').text('Tambah Siswa');
        $('#password_required').show();
        $('#password_hint').hide();
        $('#password').attr('required', 'required');
    };

    $('#formSiswa').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        // Add current CSRF token
        formData.append(csrfName, csrfHash);
        
        var idValue = $('#id').val();
        var url = idValue ? '<?= site_url('admin/siswa/ajax_update') ?>' : '<?= site_url('admin/siswa/ajax_add') ?>';
        
        // Debug log
        console.log('Form submitted, ID:', idValue);
        console.log('URL:', url);
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                // Update CSRF token
                if (response.csrf_hash) {
                    csrfHash = response.csrf_hash;
                }
                
                console.log('Response:', response);
                
                if (response.status) {
                    $('#modalSiswa').modal('hide');
                    table.ajax.reload();
                    
                    // Show SweetAlert success
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    // Show SweetAlert error
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Submit Error:', xhr.responseText);
                console.error('Status:', xhr.status);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan: ' + error + ' (Status: ' + xhr.status + ')'
                });
            }
        });
    });

    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        
        // Debug log
        console.log('Edit button clicked, ID:', id);
        
        $.get('<?= site_url('admin/siswa/ajax_edit') ?>/' + id, function(response) {
            console.log('Edit response:', response);
            
            if (response.status) {
                $('#modalTitle').text('Edit Siswa');
                $('#id').val(id); // Gunakan ID yang sudah ter-enkripsi dari data-id button
                $('#nis').val(response.data.nis);
                $('#nama').val(response.data.nama_lengkap);
                $('#jenis_kelamin').val(response.data.jenis_kelamin);
                $('#tempat_lahir').val(response.data.tempat_lahir);
                $('#tanggal_lahir').val(response.data.tanggal_lahir);
                $('#id_kelas').val(response.data.id_kelas);
                $('#alamat').val(response.data.alamat);
                $('#nama_ortu').val(response.data.nama_ortu);
                $('#no_hp_orang_tua').val(response.data.no_hp_ortu);
                $('#email').val(response.data.email || '');
                $('#no_hp').val(response.data.no_hp || '');
                $('#username').val(response.data.username || '');
                $('#status').val(response.data.user_status || 'aktif');
                $('#password').val('');
                $('#password_required').hide();
                $('#password_hint').show();
                $('#password').removeAttr('required');
                $('#modalSiswa').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        }, 'json').fail(function(xhr, status, error) {
            console.error('AJAX GET Error:', xhr.responseText);
            console.error('Status:', xhr.status);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal mengambil data: ' + error + ' (Status: ' + xhr.status + ')'
            });
        });
    });

    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data siswa yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= site_url('admin/siswa/ajax_delete') ?>', {
                    id: id,
                    [csrfName]: csrfHash
                }, function(response) {
                    // Update CSRF token
                    if (response.csrf_hash) {
                        csrfHash = response.csrf_hash;
                    }
                    
                    if (response.status) {
                        table.ajax.reload();
                        
                        // Show SweetAlert success
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        // Show SweetAlert error
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                }, 'json').fail(function(xhr, status, error) {
                    console.error('Delete Error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal menghapus: ' + error
                    });
                });
            }
        });
    });
});
</script>