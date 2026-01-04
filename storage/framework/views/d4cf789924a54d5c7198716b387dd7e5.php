

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Daftar Transaksi</h1>
            <p class="text-sm text-slate-500">Kelola semua transaksi kas masuk dan keluar</p>
        </div>
        <a href="<?php echo e(route('transactions.create')); ?>" class="btn-primary">
            <span class="iconify w-5 h-5" data-icon="heroicons:plus-circle"></span>
            Transaksi Baru
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form action="<?php echo e(route('transactions.index')); ?>" method="GET"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <label class="form-label">Tipe</label>
                    <select name="type" class="form-select">
                        <option value="">Semua</option>
                        <option value="masuk" <?php echo e(request('type') == 'masuk' ? 'selected' : ''); ?>>Masuk</option>
                        <option value="keluar" <?php echo e(request('type') == 'keluar' ? 'selected' : ''); ?>>Keluar</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approved</option>
                        <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-input" value="<?php echo e(request('start_date')); ?>">
                </div>
                <div>
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-input" value="<?php echo e(request('end_date')); ?>">
                </div>
                <div>
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-input" placeholder="Cari deskripsi..."
                        value="<?php echo e(request('search')); ?>">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">
                        <span class="iconify w-5 h-5" data-icon="heroicons:magnifying-glass"></span>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
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
                        <th>Status</th>
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
                            <td class="max-w-xs truncate"><?php echo e(Str::limit($tx->description, 50) ?: '-'); ?></td>
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
                            <td>
                                <div class="flex items-center justify-center gap-1">
                                    <a href="<?php echo e(route('transactions.show', $tx)); ?>"
                                        class="btn-icon text-slate-500 hover:text-primary-600 hover:bg-primary-50"
                                        title="Lihat">
                                        <span class="iconify w-4 h-4" data-icon="heroicons:eye"></span>
                                    </a>
                                    <?php if($tx->isPending() || $tx->isRejected()): ?>
                                        <a href="<?php echo e(route('transactions.edit', $tx)); ?>"
                                            class="btn-icon text-slate-500 hover:text-amber-600 hover:bg-amber-50" title="Edit">
                                            <span class="iconify w-4 h-4" data-icon="heroicons:pencil-square"></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <span class="iconify w-12 h-12 mx-auto mb-3 text-slate-300"
                                        data-icon="heroicons:inbox"></span>
                                    <p>Tidak ada transaksi ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($transactions->hasPages()): ?>
            <div class="px-5 py-4 border-t border-slate-100">
                <?php echo e($transactions->withQueryString()->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\manajemen_kas\resources\views/transactions/index.blade.php ENDPATH**/ ?>