<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-file-import me-2"></i>Import Data</h3>
            </div>
            <p class="text-muted">Import data siswa atau guru dari file Excel/CSV</p>
        </div>
    </div>

    <!-- Alert -->
    <div id="alert_placeholder"></div>

    <!-- Info Card -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-info">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle me-1"></i> Format Import Siswa
                </div>
                <div class="card-body">
                    <p><strong>Kolom yang diperlukan:</strong></p>
                    <ol>
                        <li>NIS (10 digit, unik)</li>
                        <li>Nama Lengkap</li>
                        <li>Jenis Kelamin (L/P)</li>
                        <li>Tempat, Tanggal Lahir</li>
                        <li>Alamat</li>
                        <li>Nama Orang Tua</li>
                        <li>No HP Orang Tua (min 10 digit)</li>
                    </ol>
                    <a href="<?= site_url('admin/import/download_template/siswa') ?>" class="btn btn-sm btn-outline-info" target="_blank">
                        <i class="fas fa-download me-1"></i> Download Template PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-success">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-info-circle me-1"></i> Format Import Guru
                </div>
                <div class="card-body">
                    <p><strong>Kolom yang diperlukan:</strong></p>
                    <ol>
                        <li>NIP (18 digit, unik)</li>
                        <li>Nama Lengkap</li>
                        <li>Jenis Kelamin (L/P)</li>
                        <li>No HP (min 10 digit)</li>
                        <li>Alamat</li>
                    </ol>
                    <a href="<?= site_url('admin/import/download_template/guru') ?>" class="btn btn-sm btn-outline-success" target="_blank">
                        <i class="fas fa-download me-1"></i> Download Template PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-upload me-1"></i> Upload File
        </div>
        <div class="card-body">
            <form id="form_import" enctype="multipart/form-data">
                <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="type" class="form-label">Tipe Data <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">-- Pilih Tipe Data --</option>
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="file" class="form-label">File Excel/CSV <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Maksimal ukuran file: 5MB. Format: XLSX, XLS, atau CSV</div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="button" class="btn btn-info" onclick="preview_file()">
                        <i class="fas fa-eye me-1"></i> Preview
                    </button>
                    <button type="submit" class="btn btn-primary" id="btn_import" disabled>
                        <i class="fas fa-upload me-1"></i> Import Data
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-redo me-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Table -->
    <div class="card shadow-sm mt-4" id="card_preview" style="display: none;">
        <div class="card-header bg-warning text-dark">
            <i class="fas fa-table me-1"></i> Preview Data (<span id="preview_count">0</span> baris)
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table_preview" class="table table-striped table-bordered table-sm">
                    <thead class="table-light" id="thead_preview">
                    </thead>
                    <tbody id="tbody_preview">
                    </tbody>
                </table>
            </div>
            <div class="alert alert-warning mt-3">
                <i class="fas fa-exclamation-triangle me-1"></i>
                <strong>Perhatian:</strong> Pastikan data sudah benar sebelum melakukan import. 
                Data yang sudah diimport tidak dapat dibatalkan secara otomatis.
            </div>
        </div>
    </div>

    <!-- Result Modal -->
    <div class="modal fade" id="modal_result" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Hasil Import</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6>Berhasil</h6>
                                    <h3 id="result_success">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h6>Gagal</h6>
                                    <h3 id="result_failed">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-dark">
                                <div class="card-body text-center">
                                    <h6>Duplikat</h6>
                                    <h3 id="result_duplicate">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h6>Detail Error (maks 10):</h6>
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <ul id="error_list" class="list-group">
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var preview_data = null;

$(document).ready(function() {
    $('#file').on('change', function() {
        var fileSize = this.files[0].size;
        if (fileSize > (5 * 1024 * 1024)) {
            showAlert('danger', 'Ukuran file terlalu besar! Maksimal 5MB');
            $(this).val('');
            $('#btn_import').prop('disabled', true);
            $('#card_preview').hide();
        }
    });
});

function preview_file() {
    var type = $('#type').val();
    var file = $('#file')[0].files[0];
    
    if (!type) {
        showAlert('warning', 'Pilih tipe data terlebih dahulu');
        return;
    }
    
    if (!file) {
        showAlert('warning', 'Pilih file terlebih dahulu');
        return;
    }
    
    var formData = new FormData();
    formData.append('file', file);
    formData.append('type', type);
    formData.append('<?= $csrf_name ?>', '<?= $csrf_hash ?>');
    
    $.ajax({
        url: '<?= site_url('admin/import/preview') ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                preview_data = response.data;
                
                // Render headers
                var thead = '<tr>';
                $.each(response.headers, function(i, header) {
                    thead += '<th>' + header + '</th>';
                });
                thead += '</tr>';
                $('#thead_preview').html(thead);
                
                // Render body
                var tbody = '';
                $.each(response.data, function(i, row) {
                    tbody += '<tr>';
                    $.each(row, function(j, cell) {
                        tbody += '<td>' + (cell || '-') + '</td>';
                    });
                    tbody += '</tr>';
                });
                $('#tbody_preview').html(tbody);
                
                $('#preview_count').text(response.total_rows);
                $('#card_preview').show();
                $('#btn_import').prop('disabled', false);
                
                showAlert('success', 'Preview berhasil. Total ' + response.total_rows + ' baris data.');
            } else {
                showAlert('danger', response.message);
                $('#card_preview').hide();
                $('#btn_import').prop('disabled', true);
            }
        },
        error: function() {
            showAlert('danger', 'Gagal memuat preview file');
            $('#card_preview').hide();
            $('#btn_import').prop('disabled', true);
        }
    });
}

$('#form_import').submit(function(e) {
    e.preventDefault();
    
    var type = $('#type').val();
    var file = $('#file')[0].files[0];
    
    if (!type || !file) {
        showAlert('warning', 'Lengkapi semua field');
        return;
    }
    
    if (!confirm('Apakah Anda yakin ingin mengimport data ini? Pastikan data sudah benar.')) {
        return;
    }
    
    var formData = new FormData();
    formData.append('file', file);
    formData.append('type', type);
    formData.append('<?= $csrf_name ?>', '<?= $csrf_hash ?>');
    
    // Show loading
    $('#btn_import').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Mengimport...');
    
    $.ajax({
        url: '<?= site_url('admin/import/proses') ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            $('#btn_import').prop('disabled', false).html('<i class="fas fa-upload me-1"></i> Import Data');
            
            if (response.status) {
                // Show result modal
                $('#result_success').text(response.success);
                $('#result_failed').text(response.failed);
                $('#result_duplicate').text(response.duplicate);
                
                var errorList = '';
                if (response.errors && response.errors.length > 0) {
                    $.each(response.errors, function(i, err) {
                        errorList += '<li class="list-group-item list-group-item-danger">' + err + '</li>';
                    });
                } else {
                    errorList = '<li class="list-group-item list-group-item-success">Tidak ada error</li>';
                }
                $('#error_list').html(errorList);
                
                $('#modal_result').modal('show');
                
                showAlert('success', response.message);
                
                // Reset form
                $('#form_import')[0].reset();
                $('#card_preview').hide();
                $('#btn_import').prop('disabled', true);
            } else {
                showAlert('danger', response.message);
            }
        },
        error: function(xhr) {
            $('#btn_import').prop('disabled', false).html('<i class="fas fa-upload me-1"></i> Import Data');
            
            var message = 'Gagal mengimport data';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            showAlert('danger', message);
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
