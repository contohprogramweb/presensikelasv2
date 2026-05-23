<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Log Approval Presensi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 5px; }
        p { text-align: center; margin-top: 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        .text-center { text-align: center; }
        .no { width: 4%; text-align: center; }
        .tanggal { width: 10%; }
        .siswa { width: 18%; }
        .kelas { width: 8%; }
        .status { width: 8%; text-align: center; }
        .pengajuan { width: 8%; text-align: center; }
        .keterangan { width: 12%; }
        .approval { width: 8%; text-align: center; }
        .alasan { width: 15%; }
    </style>
</head>
<body>
    <h2>LOG APPROVAL PRESENSI</h2>
    <p>Laporan Riwayat Approval Presensi Siswa</p>
    
    <?php if (!empty($filter_info)): ?>
    <p style="margin-bottom: 10px;">
        <?php if (!empty($filter_info['tanggal_mulai']) && !empty($filter_info['tanggal_sampai'])): ?>
            Periode: <?= date('d/m/Y', strtotime($filter_info['tanggal_mulai'])) ?> - <?= date('d/m/Y', strtotime($filter_info['tanggal_sampai'])) ?>
        <?php endif; ?>
        <?php if (!empty($filter_info['status'])): ?>
            | Status: <?= ucfirst($filter_info['status']) ?>
        <?php endif; ?>
    </p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th class="no">No</th>
                <th class="tanggal">Tanggal Approval</th>
                <th class="siswa">Nama Siswa</th>
                <th class="kelas">Kelas</th>
                <th class="status">Status Presensi</th>
                <th class="pengajuan">Pengajuan</th>
                <th class="keterangan">Keterangan</th>
                <th class="approval">Approval</th>
                <th class="alasan">Alasan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (!empty($logs)):
                foreach ($logs as $log): 
            ?>
                <tr>
                    <td class="no"><?= $no++ ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($log['tanggal_approval'])) ?></td>
                    <td><?= htmlspecialchars($log['nama_siswa']) ?></td>
                    <td><?= htmlspecialchars($log['nama_kelas'] ?? '-') ?></td>
                    <td class="text-center"><?= htmlspecialchars($log['status_presensi']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($log['status_asli']) ?></td>
                    <td><?= htmlspecialchars($log['keterangan'] ?? '-') ?></td>
                    <td class="text-center"><?= htmlspecialchars($log['status_approval']) ?></td>
                    <td><?= ($log['status_approval'] === 'ditolak') ? htmlspecialchars($log['alasan_penolakan'] ?? '-') : '-' ?></td>
                </tr>
            <?php 
                endforeach;
            else:
            ?>
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data log approval</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
