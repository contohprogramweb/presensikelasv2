<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h3><i class="fas fa-history me-2"></i>Log Approval Presensi</h3>
            <p class="text-muted">Riwayat approval presensi siswa dengan status Izin/Sakit</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-filter me-1"></i> Filter
        </div>
        <div class="card-body">
            <form id="form_filter" method="get">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="filter_tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="filter_tanggal_mulai" name="tanggal_mulai" value="<?= $default_tanggal_mulai ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="filter_tanggal_sampai" class="form-label">Tanggal Sampai</label>
                        <input type="date" class="form-control" id="filter_tanggal_sampai" name="tanggal_sampai" value="<?= $default_tanggal_sampai ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="filter_status" class="form-label">Approval</label>
                        <select class="form-select" id="filter_status" name="status">
                            <option value="">Semua Status</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary me-2" onclick="reload_table()">
                            <i class="fas fa-search me-1"></i> Tampilkan
                        </button>
                        <button type="button" class="btn btn-success me-2" onclick="preview_pdf()">
                            <i class="fas fa-file-pdf me-1"></i> PDF
                        </button>
                        <button type="button" class="btn btn-danger" onclick="export_excel()">
                            <i class="fas fa-download me-1"></i> Download PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Log -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-table me-1"></i> Daftar Log Approval
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table_log" class="table table-striped table-hover table-bordered" style="width: 100%">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Tanggal Approval</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Status Presensi</th>
                            <th>Pengajuan</th>
                            <th>Keterangan</th>
                            <th>Approval</th>
                            <th>Alasan</th>
                            <th>Approver</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
var table;

$(document).ready(function() {
    // Set default filter values from PHP
    var default_tanggal_mulai = '<?= $default_tanggal_mulai ?>';
    var default_tanggal_sampai = '<?= $default_tanggal_sampai ?>';
    
    // Initialize DataTables with default filters
    table = $('#table_log').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        },
        ajax: {
            url: '<?= site_url('kepsek/logapproval/ajax_list') ?>',
            type: 'POST',
            data: function(d) {
                d.tanggal_mulai = $('#filter_tanggal_mulai').val() || default_tanggal_mulai;
                d.tanggal_sampai = $('#filter_tanggal_sampai').val() || default_tanggal_sampai;
                d.status = $('#filter_status').val();
          
            }
        },
        columns: [
            {data: 'no'},
            {data: 'tanggal_approval'},
            {data: 'nama_siswa'},
            {data: 'nama_kelas'},
            {data: 'status_presensi'},
            {data: 'status_asli'},
            {data: 'keterangan'},
            {data: 'status_approval'},
            {
                data: 'alasan_penolakan',
                render: function(data, type, row) {
                    // Kolom Alasan hanya tampil jika approval "Ditolak"
                    if (row.status_approval && row.status_approval.includes('Ditolak')) {
                        return data ?? '-';
                    }
                    return '-';
                }
            },
            {data: 'nama_approver'}
        ],
        order: [[1, 'desc']]
    });
});

function reload_table() {
    table.ajax.reload(null, false);
}

function export_excel() {
    var params = [];
    
    if ($('#filter_tanggal_mulai').val()) {
        params.push('tanggal_mulai=' + $('#filter_tanggal_mulai').val());
    }
    if ($('#filter_tanggal_sampai').val()) {
        params.push('tanggal_sampai=' + $('#filter_tanggal_sampai').val());
    }
    if ($('#filter_status').val()) {
        params.push('status=' + $('#filter_status').val());
    }
    
    var url = '<?= site_url('kepsek/logapproval/export_excel') ?>';
    if (params.length > 0) {
        url += '?' + params.join('&');
    }
    
    window.location.href = url;
}

function preview_pdf() {
    var params = [];
    
    if ($('#filter_tanggal_mulai').val()) {
        params.push('tanggal_mulai=' + $('#filter_tanggal_mulai').val());
    }
    if ($('#filter_tanggal_sampai').val()) {
        params.push('tanggal_sampai=' + $('#filter_tanggal_sampai').val());
    }
    if ($('#filter_status').val()) {
        params.push('status=' + $('#filter_status').val());
    }
    
    var url = '<?= site_url('kepsek/logapproval/preview_pdf') ?>';
    if (params.length > 0) {
        url += '?' + params.join('&');
    }
    
    // Buka di tab baru untuk preview
    window.open(url, '_blank');
}
</script>
