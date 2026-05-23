<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Approval Presensi Siswa</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Halaman ini menampilkan presensi siswa dengan status <strong>Izin</strong> atau <strong>Sakit</strong> yang memerlukan persetujuan dari Kepala Sekolah. 
                        Presensi yang ditolak akan otomatis berubah status menjadi <strong>Alpa</strong>.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="table-approval" class="table table-hover table-bordered dt-responsive nowrap" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th width="10%">Tanggal</th>
                                    <th width="18%">Nama Siswa</th>
                                    <th width="12%">Kelas</th>
                                    <th width="15%">Guru Pengampu</th>
                                    <th width="10%">Status Presensi</th>
                                    <th width="15%">Keterangan</th>
                                    <th width="10%">Approval</th>
                                    <th width="10%" class="text-center">Aksi</th>
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

<!-- Modal Reject -->
<div class="modal fade" id="modalReject" tabindex="-1" aria-labelledby="modalRejectLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalRejectLabel"><i class="fas fa-times-circle me-2"></i>Tolak Status Presensi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-reject">
                <div class="modal-body">
                    <input type="hidden" id="reject-id-presensi">
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="4" required placeholder="Jelaskan alasan penolakan status presensi ini..."></textarea>
                        <small class="text-muted">Catatan ini akan disimpan sebagai referensi dan wajib diisi.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger" id="btn-confirm-reject">
                        <i class="fas fa-times me-1"></i> Tolak 
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#table-approval').DataTable({
        'processing': true,
        'serverSide': false,
        'ajax': {
            'url': '<?= site_url("kepsek/approval/ajax_list"); ?>',
            'type': 'POST',
            'dataSrc': 'data'
        },
        'columns': [
            { 'data': 'tanggal' },
            { 'data': 'nama_siswa' },
            { 'data': 'nama_kelas' },
            { 'data': 'nama_guru' },
            { 'data': 'status_presensi', 'orderable': false },
            { 
                'data': 'keterangan',
                'render': function(data) {
                    if (!data || data.trim() === '') {
                        return '<span class="text-muted">-</span>';
                    }
                    var displayText = data.length > 50 ? data.substring(0, 50) + '...' : data;
                    return '<span class="keterangan-cell" title="' + (data.length > 50 ? data : '') + '">' + displayText + '</span>';
                }
            },
            { 
                'data': 'status_approval', 
                'orderable': false 
            },
            { 
                'data': 'actions',
                'orderable': false,
                'className': 'text-center'
            }
        ],
        'language': {
            'processing': '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            'emptyTable': 'Tidak ada data approval',
            'zeroRecords': 'Tidak ditemukan data yang cocok',
            'info': 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            'infoEmpty': 'Tidak ada data yang tersedia',
            'infoFiltered': '(difilter dari _MAX_ total data)',
            'search': 'Cari:',
            'paginate': {
                'first': 'Pertama',
                'last': 'Terakhir',
                'next': 'Berikutnya',
                'previous': 'Sebelumnya'
            }
        },
        'order': [[0, 'desc']],
        'responsive': true,
        'drawCallback': function(settings) {
            // Re-attach event handlers after table redraw
            attachButtonHandlers();
        }
    });

    // Function to attach button handlers
    function attachButtonHandlers() {
        $('.approve-btn').off('click').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            confirmApprove(id);
        });

        $('.reject-btn').off('click').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            openRejectModal(id);
        });
    }

    // Confirm Approve
    function confirmApprove(id) {
        Swal.fire({
            title: 'Setujui Status Presensi?',
            text: 'Apakah Anda yakin ingin menyetujui status presensi ini? ',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                processApprove(id);
            }
        });
    }

    // Process Approve
    function processApprove(id) {
        $.ajax({
            url: '<?= site_url("kepsek/approval/approve"); ?>',
            type: 'POST',
            data: {
                id: id,
                '<?= $csrf_name; ?>': '<?= $csrf_hash; ?>'
            },
            dataType: 'JSON',
            beforeSend: function() {
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#table-approval').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message || 'Terjadi kesalahan saat menyetujui presensi'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan pada sistem. Silakan coba lagi.'
                });
            }
        });
    }

    // Open Reject Modal
    function openRejectModal(id) {
        $('#reject-id-presensi').val(id);
        $('#catatan').val('');
        $('#modalReject').modal('show');
    }

    // Handle Reject Form Submit
    $('#form-reject').on('submit', function(e) {
        e.preventDefault();
        
        var id = $('#reject-id-presensi').val();
        var catatan = $('#catatan').val().trim();
        
        if (!catatan) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: 'Catatan penolakan wajib diisi!'
            });
            return;
        }

        Swal.fire({
            title: 'Tolak Status Presensi?',
            text: 'Apakah Anda yakin ingin menolak sttaus presensi ini? Status presensi akan berubah menjadi Alpa.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                processReject(id, catatan);
            }
        });
    });

    // Process Reject
    function processReject(id, catatan) {
        $.ajax({
            url: '<?= site_url("kepsek/approval/reject"); ?>',
            type: 'POST',
            data: {
                id: id,
                catatan: catatan,
                '<?= $csrf_name; ?>': '<?= $csrf_hash; ?>'
            },
            dataType: 'JSON',
            beforeSend: function() {
                $('#modalReject').modal('hide');
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#table-approval').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message || 'Terjadi kesalahan saat menolak presensi'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan pada sistem. Silakan coba lagi.'
                });
            }
        });
    }

    // Auto-refresh table every 30 seconds
    setInterval(function() {
        $('#table-approval').DataTable().ajax.reload(null, false);
    }, 30000);
});
</script>
