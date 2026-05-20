<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h3><i class="fas fa-users-cog me-2"></i>Penempatan Siswa ke Kelas</h3>
            <p class="text-muted">Kelola penempatan siswa ke kelas untuk tahun ajaran <?= $tahun_ajaran->nama_tahun_ajaran ?></p>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4" id="statistik_placeholder">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Siswa</h6>
                            <h2 class="mb-0" id="total_siswa">0</h2>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Sudah Ditempatkan</h6>
                            <h2 class="mb-0" id="sudah_ditempatkan">0</h2>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Belum Ditempatkan</h6>
                            <h2 class="mb-0" id="belum_ditempatkan">0</h2>
                        </div>
                        <i class="fas fa-exclamation-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert -->
    <div id="alert_placeholder"></div>

    <!-- Tabel Kelas -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-school me-1"></i> Daftar Kelas
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table_kelas" class="table table-striped table-hover table-bordered" style="width: 100%">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Kelas</th>
                            <th>Wali Kelas</th>
                            <th>Jumlah Siswa</th>
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

<!-- Modal Kelola Siswa -->
<div class="modal fade" id="modal_kelola_siswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal_kelola_title">Kelola Siswa - <span id="nama_kelas_display"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_kelas_current">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <i class="fas fa-user-plus me-1"></i> Siswa Tersedia
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="search_tersedia" placeholder="Cari siswa...">
                                </div>
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th width="5%"><input type="checkbox" id="check_all_tersedia"></th>
                                                <th>NIS</th>
                                                <th>Nama</th>
                                                <th>JK</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_tersedia">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-users me-1"></i> Siswa di Kelas Ini
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="search_ditempatkan" placeholder="Cari siswa...">
                                </div>
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th width="5%"></th>
                                                <th>NIS</th>
                                                <th>Nama</th>
                                                <th>JK</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_ditempatkan">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="simpan_penempatan()">
                    <i class="fas fa-save me-1"></i> Simpan Penempatan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
var table_kelas;
var available_students = [];
var placed_students = [];

$(document).ready(function() {
    // Load statistik
    load_statistik();
    
    // Initialize DataTables Kelas
    table_kelas = $('#table_kelas').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        pageLength: 10,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        },
        ajax: {
            url: '<?= site_url('admin/kelassiswa/ajax_get_kelas') ?>',
            type: 'POST',
            dataSrc: 'data'
        },
        columns: [
            {data: null, orderable: false, render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            {data: 'nama_kelas'},
            {data: 'wali_kelas'},
            {data: 'jumlah_siswa'},
            {data: 'action', orderable: false}
        ]
    });
    
    // Search functionality
    $('#search_tersedia').on('keyup', function() {
        filter_students('tersedia', $(this).val());
    });
    
    $('#search_ditempatkan').on('keyup', function() {
        filter_students('ditempatkan', $(this).val());
    });
    
    // Check all
    $('#check_all_tersedia').on('change', function() {
        var checked = $(this).is(':checked');
        $('#tbody_tersedia input[type="checkbox"]').prop('checked', checked);
    });
});

function load_statistik() {
    $.ajax({
        url: '<?= site_url('admin/kelassiswa/ajax_get_statistik') ?>',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#total_siswa').text(data.total_siswa);
            $('#sudah_ditempatkan').text(data.sudah_ditempatkan);
            $('#belum_ditempatkan').text(data.belum_ditempatkan);
        }
    });
}

function kelola_siswa(encrypted_id_kelas, nama_kelas) {
    $('#id_kelas_current').val(encrypted_id_kelas);
    $('#nama_kelas_display').text(nama_kelas);
    $('#modal_kelola_title').html('Kelola Siswa - <span class="fw-bold">' + nama_kelas + '</span>');
    
    // Load siswa
    load_siswa(encrypted_id_kelas);
    
    $('#modal_kelola_siswa').modal('show');
}

function load_siswa(encrypted_id_kelas) {
    $.ajax({
        url: '<?= site_url('admin/kelassiswa/ajax_get_siswa') ?>/' + encrypted_id_kelas,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                available_students = response.tersedia;
                placed_students = response.ditempatkan;
                
                render_students('tersedia', available_students);
                render_students('ditempatkan', placed_students);
            }
        }
    });
}

function render_students(type, students) {
    var tbody = type === 'tersedia' ? $('#tbody_tersedia') : $('#tbody_ditempatkan');
    tbody.empty();
    
    if (students.length === 0) {
        tbody.append('<tr><td colspan="' + (type === 'tersedia' ? '4' : '5') + '" class="text-center text-muted">Tidak ada data</td></tr>');
        return;
    }
    
    $.each(students, function(i, student) {
        var row = '<tr>';
        
        if (type === 'tersedia') {
            row += '<td><input type="checkbox" class="check_siswa" value="' + encrypt_id(student.id) + '"></td>';
        } else {
            row += '<td></td>';
        }
        
        row += '<td>' + student.nis + '</td>';
        row += '<td>' + student.nama + '</td>';
        row += '<td>' + student.jk + '</td>';
        
        if (type === 'ditempatkan') {
            row += '<td><button type="button" class="btn btn-sm btn-danger" onclick="hapus_siswa(\'' + encrypt_id(student.id) + '\')"><i class="fas fa-times"></i></button></td>';
        }
        
        row += '</tr>';
        tbody.append(row);
    });
}

function filter_students(type, keyword) {
    keyword = keyword.toLowerCase();
    var students = type === 'tersedia' ? available_students : placed_students;
    
    var filtered = students.filter(function(s) {
        return s.nis.toLowerCase().includes(keyword) || s.nama.toLowerCase().includes(keyword);
    });
    
    render_students(type, filtered);
}

function simpan_penempatan() {
    var id_kelas = $('#id_kelas_current').val();
    var id_siswa_arr = [];
    
    $('#tbody_tersedia input[type="checkbox"]:checked').each(function() {
        id_siswa_arr.push($(this).val());
    });
    
    if (id_siswa_arr.length === 0) {
        showAlert('warning', 'Pilih minimal 1 siswa untuk ditempatkan');
        return;
    }
    
    $.ajax({
        url: '<?= site_url('admin/kelassiswa/simpan_penempatan') ?>',
        type: 'POST',
        data: {
            id_kelas: id_kelas,
            id_siswa: id_siswa_arr,
            '<?= $csrf_name ?>': '<?= $csrf_hash ?>'
        },
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                showAlert('success', response.message);
                $('#modal_kelola_siswa').modal('hide');
                table_kelas.ajax.reload(null, false);
                load_statistik();
            } else {
                showAlert('danger', response.message);
            }
        },
        error: function() {
            showAlert('danger', 'Terjadi kesalahan saat menyimpan data');
        }
    });
}

function hapus_siswa(encrypted_id_siswa) {
    if (!confirm('Apakah Anda yakin ingin mengeluarkan siswa ini dari kelas?')) {
        return;
    }
    
    $.ajax({
        url: '<?= site_url('admin/kelassiswa/ajax_hapus_siswa') ?>',
        type: 'POST',
        data: {
            id_siswa: encrypted_id_siswa,
            '<?= $csrf_name ?>': '<?= $csrf_hash ?>'
        },
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                showAlert('success', response.message);
                load_siswa($('#id_kelas_current').val());
                table_kelas.ajax.reload(null, false);
                load_statistik();
            } else {
                showAlert('danger', response.message);
            }
        },
        error: function() {
            showAlert('danger', 'Terjadi kesalahan saat menghapus data');
        }
    });
}

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

// Helper function for encryption (same as PHP encrypt_id)
function encrypt_id(id) {
    // This is a simplified version - in production, use proper encryption
    return btoa('smp_galang_kasih_2025|' + id).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '.');
}
</script>
