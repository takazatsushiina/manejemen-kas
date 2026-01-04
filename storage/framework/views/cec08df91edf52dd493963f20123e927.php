

<?php $__env->startSection('content'); ?>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <!-- Total Kas Masuk -->
        <div class="stat-card">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shrink-0">
                    <span class="iconify w-6 h-6 text-white" data-icon="heroicons:arrow-down-circle"></span>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Kas Masuk</p>
                    <p class="text-xl font-bold text-emerald-600">Rp <?php echo e(number_format($totalMasuk, 0, ',', '.')); ?></p>
                </div>
            </div>
        </div>

        <!-- Total Kas Keluar -->
        <div class="stat-card">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center shrink-0">
                    <span class="iconify w-6 h-6 text-white" data-icon="heroicons:arrow-up-circle"></span>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Kas Keluar</p>
                    <p class="text-xl font-bold text-red-600">Rp <?php echo e(number_format($totalKeluar, 0, ',', '.')); ?></p>
                </div>
            </div>
        </div>

        <!-- Saldo Kas -->
        <div class="stat-card">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shrink-0">
                    <span class="iconify w-6 h-6 text-white" data-icon="heroicons:wallet"></span>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Saldo Kas</p>
                    <p class="text-xl font-bold text-slate-800">Rp <?php echo e(number_format($saldoKas, 0, ',', '.')); ?></p>
                </div>
            </div>
        </div>

        <!-- Kas Kecil -->
        <div class="stat-card">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shrink-0">
                    <span class="iconify w-6 h-6 text-white" data-icon="heroicons:currency-dollar"></span>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Kas Kecil</p>
                    <p class="text-xl font-bold text-slate-800">Rp <?php echo e(number_format($pettyCashBalance, 0, ',', '.')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Alert for Approvers -->
    <?php if(auth()->user()->canApproveTransaction() && ($pendingTransactions > 0 || $pendingReconciliations > 0)): ?>
        <div class="alert-warning mb-6">
            <span class="iconify w-6 h-6 text-amber-600 shrink-0" data-icon="heroicons:exclamation-triangle"></span>
            <div class="flex-1">
                <p class="font-medium text-amber-800">Perlu Perhatian</p>
                <p class="text-sm mt-0.5">
                    <?php if($pendingTransactions > 0): ?>
                        <?php echo e($pendingTransactions); ?> transaksi menunggu validasi.
                    <?php endif; ?>
                    <?php if($pendingReconciliations > 0): ?>
                        <?php echo e($pendingReconciliations); ?> rekonsiliasi menunggu persetujuan.
                    <?php endif; ?>
                </p>
            </div>
            <a href="<?php echo e(route('transactions.pending')); ?>" class="btn-sm btn-warning shrink-0">
                Lihat Sekarang
            </a>
        </div>
    <?php endif; ?>

    <!-- Chart & Quick Actions Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Chart -->
        <div class="lg:col-span-2 card">
            <div class="card-header flex items-center gap-2">
                <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:chart-bar"></span>
                Grafik Arus Kas (6 Bulan Terakhir)
            </div>
            <div class="card-body">
                <canvas id="cashFlowChart" height="120"></canvas>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header flex items-center gap-2">
                <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:bolt"></span>
                Aksi Cepat
            </div>
            <div class="card-body space-y-3">
                <a href="<?php echo e(route('transactions.create')); ?>" class="btn-primary w-full justify-center">
                    <span class="iconify w-5 h-5" data-icon="heroicons:plus-circle"></span>
                    Transaksi Baru
                </a>
                <a href="<?php echo e(route('petty-cash.index')); ?>" class="btn-outline w-full justify-center">
                    <span class="iconify w-5 h-5" data-icon="heroicons:wallet"></span>
                    Kelola Kas Kecil
                </a>
                <a href="<?php echo e(route('reports.index')); ?>" class="btn-outline w-full justify-center">
                    <span class="iconify w-5 h-5" data-icon="heroicons:document-chart-bar"></span>
                    Lihat Laporan
                </a>
                <?php if(auth()->user()->canApproveTransaction()): ?>
                    <a href="<?php echo e(route('reconciliations.create')); ?>" class="btn-outline w-full justify-center">
                        <span class="iconify w-5 h-5" data-icon="heroicons:arrows-right-left"></span>
                        Buat Rekonsiliasi
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="table-wrapper">
        <div class="card-header flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:clock"></span>
                Transaksi Terbaru
            </div>
            <a href="<?php echo e(route('transactions.index')); ?>" class="btn-sm btn-outline-primary">
                Lihat Semua
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="whitespace-nowrap"><?php echo e($tx->transaction_date->format('d/m/Y')); ?></td>
                            <td class="max-w-xs truncate"><?php echo e(Str::limit($tx->description, 40) ?: '-'); ?></td>
                            <td>
                                <?php if($tx->isMasuk()): ?>
                                    <span class="badge-success">
                                        <span class="iconify w-3 h-3 mr-1" data-icon="heroicons:arrow-down"></span>
                                        Masuk
                                    </span>
                                <?php else: ?>
                                    <span class="badge-danger">
                                        <span class="iconify w-3 h-3 mr-1" data-icon="heroicons:arrow-up"></span>
                                        Keluar
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="font-semibold <?php echo e($tx->isMasuk() ? 'text-emerald-600' : 'text-red-600'); ?>">
                                <?php echo e($tx->isMasuk() ? '+' : '-'); ?><?php echo e($tx->formatted_amount); ?>

                            </td>
                            <td>
                                <?php if($tx->isPending()): ?>
                                    <span class="badge-warning">Pending</span>
                                <?php elseif($tx->isApproved()): ?>
                                    <span class="badge-success">Approved</span>
                                <?php else: ?>
                                    <span class="badge-danger">Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td class="whitespace-nowrap"><?php echo e($tx->user->name); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <span class="iconify w-12 h-12 mx-auto mb-3 text-slate-300"
                                        data-icon="heroicons:inbox"></span>
                                    <p>Belum ada transaksi</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        const monthlyData = <?php echo json_encode($monthlyData, 15, 512) ?>;

        if (monthlyData.length > 0) {
            const ctx = document.getElementById('cashFlowChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthlyData.map(d => d.month),
                    datasets: [
                        {
                            label: 'Kas Masuk',
                            data: monthlyData.map(d => d.masuk),
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderRadius: 6,
                        },
                        {
                            label: 'Kas Keluar',
                            data: monthlyData.map(d => d.keluar),
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    family: 'Inter',
                                    size: 12
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                callback: function (value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                },
                                font: {
                                    family: 'Inter',
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: 'Inter',
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\manajemen_kas\resources\views/dashboard/index.blade.php ENDPATH**/ ?>