

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:arrows-right-left"></span>
                Detail Rekonsiliasi #<?php echo e($reconciliation->id); ?>

            </div>
            <a href="<?php echo e(route('reconciliations.index')); ?>" class="btn-sm btn-secondary">
                <span class="iconify w-4 h-4" data-icon="heroicons:arrow-left"></span>
                Kembali
            </a>
        </div>
        <div class="card-body">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="p-4 bg-primary-50 rounded-xl text-center">
                    <p class="text-sm text-primary-600 mb-1">Saldo Bank</p>
                    <p class="text-xl font-bold text-primary-700"><?php echo e($reconciliation->formatted_bank_balance); ?></p>
                </div>
                <div class="p-4 bg-slate-100 rounded-xl text-center">
                    <p class="text-sm text-slate-600 mb-1">Saldo Buku Kas</p>
                    <p class="text-xl font-bold text-slate-700"><?php echo e($reconciliation->formatted_cash_book_balance); ?></p>
                </div>
                <div class="p-4 <?php echo e($reconciliation->difference == 0 ? 'bg-emerald-50' : 'bg-red-50'); ?> rounded-xl text-center">
                    <p class="text-sm <?php echo e($reconciliation->difference == 0 ? 'text-emerald-600' : 'text-red-600'); ?> mb-1">Selisih</p>
                    <p class="text-xl font-bold <?php echo e($reconciliation->difference == 0 ? 'text-emerald-700' : 'text-red-700'); ?>">
                        <?php echo e($reconciliation->formatted_difference); ?>

                    </p>
                </div>
            </div>
            
            <div class="border-t border-slate-100 pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-slate-500 mb-1">Tanggal</p>
                            <p class="font-medium text-slate-800"><?php echo e($reconciliation->reconciliation_date->format('d F Y')); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 mb-1">Status</p>
                            <?php if($reconciliation->difference == 0): ?>
                                <span class="badge-success">Seimbang</span>
                            <?php else: ?>
                                <span class="badge-warning">Ada Selisih</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-slate-500 mb-1">Dibuat Oleh</p>
                            <p class="font-medium text-slate-800"><?php echo e($reconciliation->user->name); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 mb-1">Dibuat Pada</p>
                            <p class="font-medium text-slate-800"><?php echo e($reconciliation->created_at->format('d/m/Y H:i')); ?></p>
                        </div>
                    </div>
                </div>
                
                <?php if($reconciliation->notes): ?>
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <p class="text-sm text-slate-500 mb-2">Catatan</p>
                    <p class="text-slate-700"><?php echo nl2br(e($reconciliation->notes)); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\manajemen_kas\resources\views/reconciliations/show.blade.php ENDPATH**/ ?>