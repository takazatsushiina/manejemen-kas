

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800">Transaksi Menunggu Validasi</h1>
        <p class="text-sm text-slate-500"><?php echo e($transactions->total()); ?> transaksi perlu divalidasi</p>
    </div>

    <!-- Pending Transactions Table -->
    <div class="table-wrapper">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Dibuat Oleh</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <span class="font-medium text-slate-800">#<?php echo e($tx->id); ?></span>
                            </td>
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
                            <td class="font-bold <?php echo e($tx->isMasuk() ? 'text-emerald-600' : 'text-red-600'); ?>">
                                <?php echo e($tx->formatted_amount); ?>

                            </td>
                            <td class="whitespace-nowrap"><?php echo e($tx->user->name); ?></td>
                            <td class="text-center">
                                <a href="<?php echo e(route('transactions.show', $tx)); ?>" class="btn-sm btn-primary">
                                    <span class="iconify w-4 h-4" data-icon="heroicons:eye"></span>
                                    Review
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7">
                                <div class="text-center py-12">
                                    <span class="iconify w-16 h-16 mx-auto mb-4 text-emerald-300"
                                        data-icon="heroicons:check-circle"></span>
                                    <p class="text-lg font-medium text-slate-700 mb-1">Semua transaksi sudah divalidasi!</p>
                                    <p class="text-sm text-slate-500">Tidak ada transaksi yang menunggu validasi saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($transactions->hasPages()): ?>
            <div class="px-5 py-4 border-t border-slate-100">
                <?php echo e($transactions->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\manajemen_kas\resources\views/transactions/pending.blade.php ENDPATH**/ ?>