<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Arus Kas - <?php echo e($validated['start_date']); ?> s/d <?php echo e($validated['end_date']); ?></title>
    <style>
        * {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        body {
            padding: 40px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #4f46e5;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
        }
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .summary-box {
            flex: 1;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            margin: 0 10px;
        }
        .summary-box.masuk { background: #dcfce7; color: #16a34a; }
        .summary-box.keluar { background: #fee2e2; color: #dc2626; }
        .summary-box.net { background: #e0e7ff; color: #4f46e5; }
        .summary-box h3 { font-size: 24px; margin-bottom: 5px; }
        .summary-box p { font-size: 12px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8fafc;
            font-weight: bold;
            color: #475569;
        }
        .text-success { color: #16a34a; }
        .text-danger { color: #dc2626; }
        .text-right { text-align: right; }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ARUS KAS</h1>
        <p>Periode: <?php echo e(\Carbon\Carbon::parse($validated['start_date'])->format('d F Y')); ?> - <?php echo e(\Carbon\Carbon::parse($validated['end_date'])->format('d F Y')); ?></p>
        <p>Dicetak pada: <?php echo e(now()->format('d F Y H:i')); ?></p>
    </div>

    <div class="summary">
        <div class="summary-box masuk">
            <h3>Rp <?php echo e(number_format($totalMasuk, 0, ',', '.')); ?></h3>
            <p>Total Kas Masuk</p>
        </div>
        <div class="summary-box keluar">
            <h3>Rp <?php echo e(number_format($totalKeluar, 0, ',', '.')); ?></h3>
            <p>Total Kas Keluar</p>
        </div>
        <div class="summary-box net">
            <h3>Rp <?php echo e(number_format($totalMasuk - $totalKeluar, 0, ',', '.')); ?></h3>
            <p>Arus Kas Bersih</p>
        </div>
    </div>

    <h3>Detail Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Tipe</th>
                <th class="text-right">Nominal</th>
                <th>Oleh</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($tx->transaction_date->format('d/m/Y')); ?></td>
                <td><?php echo e($tx->description ?: '-'); ?></td>
                <td><?php echo e($tx->isMasuk() ? 'Masuk' : 'Keluar'); ?></td>
                <td class="text-right <?php echo e($tx->isMasuk() ? 'text-success' : 'text-danger'); ?>">
                    <?php echo e($tx->isMasuk() ? '+' : '-'); ?>Rp <?php echo e(number_format($tx->amount, 0, ',', '.')); ?>

                </td>
                <td><?php echo e($tx->user->name); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada transaksi</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate oleh Sistem Manajemen Uang Kas (SIMKAS)</p>
    </div>

    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Cetak Laporan
        </button>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\manajemen_kas\resources\views/reports/export.blade.php ENDPATH**/ ?>