

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800">Log Aktivitas</h1>
        <p class="text-sm text-slate-500">Riwayat aktivitas semua pengguna</p>
    </div>

    <!-- Activity Logs Table -->
    <div class="table-wrapper">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="whitespace-nowrap"><?php echo e($log->created_at->format('d/m/Y H:i:s')); ?></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="avatar avatar-sm">
                                        <?php echo e(strtoupper(substr($log->user->name ?? 'X', 0, 1))); ?>

                                    </div>
                                    <span class="text-slate-700"><?php echo e($log->user->name ?? 'Deleted User'); ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-secondary"><?php echo e($log->action); ?></span>
                            </td>
                            <td class="max-w-xs truncate"><?php echo e(Str::limit($log->description, 60) ?: '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <span class="iconify w-12 h-12 mx-auto mb-3 text-slate-300"
                                        data-icon="heroicons:clipboard-document-list"></span>
                                    <p>Belum ada aktivitas</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($logs->hasPages()): ?>
            <div class="px-5 py-4 border-t border-slate-100">
                <?php echo e($logs->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\manajemen_kas\resources\views/users/activity-logs.blade.php ENDPATH**/ ?>