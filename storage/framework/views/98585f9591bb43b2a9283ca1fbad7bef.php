

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="table-card">
            <div class="p-4 border-bottom">
                <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Transaksi #<?php echo e($transaction->id); ?></h5>
            </div>
            <div class="p-4">
                <form action="<?php echo e(route('transactions.update', $transaction)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tipe Transaksi <span class="text-danger">*</span></label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="type" id="type_masuk" value="masuk" <?php echo e(old('type', $transaction->type) == 'masuk' ? 'checked' : ''); ?>>
                                <label class="btn btn-outline-success" for="type_masuk">
                                    <i class="bi bi-arrow-down-circle me-2"></i>Kas Masuk
                                </label>
                                
                                <input type="radio" class="btn-check" name="type" id="type_keluar" value="keluar" <?php echo e(old('type', $transaction->type) == 'keluar' ? 'checked' : ''); ?>>
                                <label class="btn btn-outline-danger" for="type_keluar">
                                    <i class="bi bi-arrow-up-circle me-2"></i>Kas Keluar
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="transaction_date" class="form-label fw-medium">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="transaction_date" name="transaction_date" 
                                   value="<?php echo e(old('transaction_date', $transaction->transaction_date->format('Y-m-d'))); ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="amount" class="form-label fw-medium">Nominal <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="amount" name="amount" 
                                   value="<?php echo e(old('amount', $transaction->amount)); ?>" min="0" step="1000" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-medium">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                                  placeholder="Keterangan transaksi..."><?php echo e(old('description', $transaction->description)); ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="receipt" class="form-label fw-medium">Tambah Bukti Transaksi</label>
                        <input type="file" class="form-control" id="receipt" name="receipt" 
                               accept=".jpg,.jpeg,.png,.pdf">
                        <div class="form-text">Format: JPG, PNG, PDF. Maksimal 5MB.</div>
                    </div>

                    <?php if($transaction->receipts->count() > 0): ?>
                    <div class="mb-4">
                        <label class="form-label fw-medium">Bukti yang Sudah Ada</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php $__currentLoopData = $transaction->receipts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($receipt->file_url); ?>" target="_blank" class="badge bg-light text-dark p-2">
                                <i class="bi bi-paperclip me-1"></i><?php echo e($receipt->file_name); ?>

                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                        </button>
                        <a href="<?php echo e(route('transactions.show', $transaction)); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\manajemen_kas\resources\views/transactions/edit.blade.php ENDPATH**/ ?>