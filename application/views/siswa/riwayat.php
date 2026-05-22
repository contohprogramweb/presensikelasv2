<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h3><i class="fas fa-history me-2"></i>Riwayat Presensi Saya</h3>
            <p class="text-muted">Lihat riwayat kehadiran Anda di semua mata pelajaran</p>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4" id="statistik_placeholder">
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="mb-0">Hadir</h6>
                    <h2 class="mb-0" id="stat_hadir">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="mb-0">Izin</h6>
                    <h2 class="mb-0" id="stat_izin">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body text-center">
                    <h6 class="mb-0">Sakit</h6>
                    <h2 class="mb-0" id="stat_sakit">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="mb-0">Alpa</h6>
                    <h2 class="mb-0" id="stat_alpa">0</h2>
                </div>
            </div>
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
                        <input type="date" class="form-control" id="filter_tanggal_mulai" name="tanggal_mulai">
                    </div>
                    <div class="col-md-3">
                        <label for="filter_tanggal_sampai" class="form-label">Tanggal Sampai</label>
                        <input type="date" class="form-control" id="filter_tanggal_sampai" name="tanggal_sampai">
                    </div>
                    <div class="col-md-3">
                        <label for="filter_status" class="form-label">Status</label>
                        <select class="form-select" id="filter_status" name="status">
                            <option value="">Semua Status</option>
                            <option value="Hadir">Hadir</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Alpa">Alpa</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary me-2" onclick="reload_table()">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-redo me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Riwayat -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-table me-1"></i> Daftar Presensi
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table_riwayat" class="table table-striped table-hover table-bordered" style="width: 100%">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Materi</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Status Approval</th>
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
    // Load statistik
    load_statistik();
    
    // Initialize DataTables
    table = $('#table_riwayat').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        },
        ajax: {
            url: '<?= site_url('siswa/riwayat/ajax_list') ?>',
            type: 'POST',
            data: function(d) {
                d.tanggal_mulai = $('#filter_tanggal_mulai').val();
                d.tanggal_sampai = $('#filter_tanggal_sampai').val();
                d.status = $('#filter_status').val();
            },
            error: function(xhr, errorStatus, errorThrown) {
                console.log('AJAX Error:', errorStatus, errorThrown);
                console.log('Response:', xhr.responseText);
                alert('Terjadi kesalahan saat memuat data: ' + xhr.responseText);
            }
        },
        columns: [
            {
                data: 'no',
                orderable: false
            },
            {
                data: 'tanggal',
                orderable: true
            },
            {
                data: 'hari',
                orderable: true
            },
            {
                data: 'nama_mapel',
                orderable: true
            },
            {
                data: 'nama_guru',
                orderable: true
            },
            { 
                data: 'materi',
                orderable: false
            },
            {
                data: 'status',
                orderable: true
            },
            {
                data: 'keterangan',
                orderable: false
            },
            {
                data: 'status_approval',
                orderable: false
            }
        ],
        order: [[1, 'desc']],
    });
});

function load_statistik() {
    $.ajax({
        url: '<?= site_url('siswa/riwayat/ajax_statistik') ?>',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.status) {
                $('#stat_hadir').text(data.hadir);
                $('#stat_izin').text(data.izin);
                $('#stat_sakit').text(data.sakit);
                $('#stat_alpa').text(data.alpa);
            }
        }
    });
}

function reload_table() {
    table.ajax.reload(null, false);
    load_statistik();
}
</script>
