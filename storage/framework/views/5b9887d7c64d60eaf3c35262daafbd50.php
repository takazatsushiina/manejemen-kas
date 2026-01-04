

<?php $__env->startSection('content'); ?>
    <div class="max-w-xl mx-auto">
        <div class="card">
            <div class="card-header flex items-center gap-2">
                <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:arrows-right-left"></span>
                Buat Rekonsiliasi Baru
            </div>
            <div class="card-body space-y-6">
                <!-- Current Balance Info -->
                <div class="p-4 bg-sky-50 border border-sky-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <span class="iconify w-5 h-5 text-sky-500 shrink-0 mt-0.5"
                            data-icon="heroicons:information-circle"></span>
                        <div>
                            <p class="font-medium text-sky-800 mb-1">Saldo Buku Kas Saat Ini</p>
                            <p class="text-2xl font-bold text-sky-700">Rp <?php echo e(number_format($cashBookBalance, 0, ',', '.')); ?>

                            </p>
                            <p class="text-xs text-sky-600 mt-1">Berdasarkan transaksi yang sudah disetujui</p>
                        </div>
                    </div>
                </div>

                <form action="<?php echo e(route('reconciliations.store')); ?>" method="POST" class="space-y-5">
                    <?php echo csrf_field(); ?>

                    <!-- Date -->
                    <div>
                        <label for="reconciliation_date" class="form-label">Tanggal Rekonsiliasi <span
                                class="text-red-500">*</span></label>
                        <input type="date" id="reconciliation_date" name="reconciliation_date" class="form-input"
                            value="<?php echo e(old('reconciliation_date', date('Y-m-d'))); ?>" required>
                    </div>

                    <!-- Bank Balance -->
                    <div>
                        <label for="bank_balance" class="form-label">Saldo Bank <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-medium">Rp</span>
                            <input type="number" id="bank_balance" name="bank_balance" class="form-input pl-12"
                                value="<?php echo e(old('bank_balance')); ?>" step="0.01" required
                                placeholder="Masukkan saldo dari rekening koran">
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Masukkan saldo sesuai dengan rekening koran/mutasi bank</p>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea id="notes" name="notes" rows="3" class="form-input resize-none"
                            placeholder="Catatan tambahan (opsional)"><?php echo e(old('notes')); ?></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <button type="submit" class="btn-primary">
                            <span class="iconify w-5 h-5" data-icon="heroicons:check-circle"></span>
                            Buat Rekonsiliasi
                        </button>
                        <a href="<?php echo e(route('reconciliations.index')); ?>" class="btn-secondary">
                            <span class="iconify w-5 h-5" data-icon="heroicons:x-mark"></span>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\manajemen_kas\resources\views/reconciliations/create.blade.php ENDPATH**/ ?>