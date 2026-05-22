<div class="content-wrapper">

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Profil Saya</h5>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?= $this->session->flashdata('success'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?= $this->session->flashdata('error'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <ul class="nav nav-tabs mb-4" id="profilTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" 
                                    type="button" role="tab"><i class="fas fa-user-edit me-2"></i>Edit Profil</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" 
                                    type="button" role="tab"><i class="fas fa-key me-2"></i>Ubah Password</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="profilTabContent">
                        <!-- Edit Profil Tab -->
                        <div class="tab-pane fade show active" id="edit" role="tabpanel">
                            <form id="formEditProfil" enctype="multipart/form-data">
                                <input type="hidden" name="<?= $csrf_name; ?>" value="<?= $csrf_hash; ?>">
                                
                                <div class="row">
                                    <div class="col-md-4 text-center mb-3">
                                        <img src="<?= !empty($profile->foto_profil) ? base_url('assets/uploads/profil/' . $profile->foto_profil) : base_url('assets/img/default.png'); ?>" 
                                             alt="Foto Profil" class="rounded-circle mb-3" id="previewFoto" 
                                             style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #0d6efd;">
                                        <div class="mt-2">
                                            <label for="foto_profil" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-upload me-1"></i>Upload Foto
                                            </label>
                                            <input type="file" class="d-none" id="foto_profil" name="foto_profil" accept="image/jpeg,image/png">
                                        </div>
                                        <small class="text-muted d-block mt-2">Max 2MB (JPG/PNG)</small>
                                    </div>
                                    
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                                   value="<?= html_escape($profile->nama_lengkap); ?>" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?= html_escape($profile->email ?? ''); ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="no_hp" class="form-label">No HP</label>
                                            <input type="text" class="form-control" id="no_hp" name="no_hp" 
                                                   value="<?= html_escape($profile->no_hp ?? ''); ?>" placeholder="08xxxxxxxxxx">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" class="form-control" value="<?= html_escape($profile->username); ?>" disabled>
                                            <small class="text-muted">Username tidak dapat diubah</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <input type="text" class="form-control" value="<?= ucfirst($profile->role); ?>" disabled>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border-top pt-3 mt-3">
                                    <button type="submit" class="btn btn-primary" id="btnSimpanProfil">
                                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                                    </button>
                                    <a href="<?= site_url('dashboard'); ?>" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                </div>
                            </form>
                        </div>

                        <!-- Ubah Password Tab -->
                        <div class="tab-pane fade" id="password" role="tabpanel">
                            <form id="formUbahPassword">
                                <input type="hidden" name="<?= $csrf_name; ?>" value="<?= $csrf_hash; ?>">
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Password minimal 8 karakter, kombinasi huruf dan angka.
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_lama" class="form-label">Password Lama <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_lama" name="password_lama" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_baru" class="form-label">Password Baru <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_baru" name="password_baru" 
                                           minlength="8" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="konfirmasi_password" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" required>
                                </div>
                                
                                <div class="border-top pt-3 mt-3">
                                    <button type="submit" class="btn btn-warning" id="btnUbahPassword">
                                        <i class="fas fa-key me-2"></i>Ubah Password
                                    </button>
                                    <a href="<?= site_url('dashboard'); ?>" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<script>
$(document).ready(function() {
    // Preview foto sebelum upload
    $('#foto_profil').on('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            var file = e.target.files[0];
            
            // Validasi ukuran file (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File terlalu besar!',
                    text: 'Ukuran file maksimal 2MB'
                });
                $(this).val('');
                return false;
            }
            
            // Validasi tipe file
            if (!['image/jpeg', 'image/png'].includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format tidak didukung!',
                    text: 'Hanya file JPG dan PNG yang diterima'
                });
                $(this).val('');
                return false;
            }
            
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#previewFoto').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Submit edit profil
    $('#formEditProfil').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: '<?= site_url("profil/update_profil"); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#btnSimpanProfil').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
            },
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan pada server'
                });
            },
            complete: function() {
                $('#btnSimpanProfil').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Simpan Perubahan');
            }
        });
    });

    // Submit ubah password
    $('#formUbahPassword').on('submit', function(e) {
        e.preventDefault();
        
        var password_baru = $('#password_baru').val();
        var konfirmasi_password = $('#konfirmasi_password').val();
        
        if (password_baru !== konfirmasi_password) {
            Swal.fire({
                icon: 'error',
                title: 'Password tidak cocok!',
                text: 'Konfirmasi password baru harus sama dengan password baru'
            });
            return false;
        }
        
        $.ajax({
            url: '<?= site_url("profil/update_password"); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#btnUbahPassword').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
            },
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        $('#formUbahPassword')[0].reset();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan pada server'
                });
            },
            complete: function() {
                $('#btnUbahPassword').prop('disabled', false).html('<i class="fas fa-key me-2"></i>Ubah Password');
            }
        });
    });

    // Form validation
    $('#formEditProfil').validate({
        rules: {
            nama_lengkap: {
                required: true,
                maxlength: 100
            },
            email: {
                email: true,
                maxlength: 100
            },
            no_hp: {
                number: true,
                maxlength: 20
            }
        },
        messages: {
            nama_lengkap: {
                required: 'Nama lengkap wajib diisi',
                maxlength: 'Maksimal 100 karakter'
            },
            email: {
                email: 'Format email tidak valid'
            },
            no_hp: {
                number: 'Hanya angka yang diperbolehkan'
            }
        },
        errorClass: 'text-danger small',
        errorElement: 'div'
    });

    $('#formUbahPassword').validate({
        rules: {
            password_lama: 'required',
            password_baru: {
                required: true,
                minlength: 8,
                pattern: /(?=.*[a-zA-Z])(?=.*[0-9])/
            },
            konfirmasi_password: {
                required: true,
                equalTo: '#password_baru'
            }
        },
        messages: {
            password_lama: 'Password lama wajib diisi',
            password_baru: {
                required: 'Password baru wajib diisi',
                minlength: 'Minimal 8 karakter',
                pattern: 'Kombinasi huruf dan angka'
            },
            konfirmasi_password: {
                required: 'Konfirmasi password wajib diisi',
                equalTo: 'Password tidak sama'
            }
        },
        errorClass: 'text-danger small',
        errorElement: 'div'
    });
});
</script>
