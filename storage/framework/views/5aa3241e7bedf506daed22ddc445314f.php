

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Manajemen Pengguna</h1>
            <p class="text-sm text-slate-500">Kelola akun pengguna sistem</p>
        </div>
        <a href="<?php echo e(route('users.create')); ?>" class="btn-primary">
            <span class="iconify w-5 h-5" data-icon="heroicons:plus-circle"></span>
            Tambah Pengguna
        </a>
    </div>

    <!-- Users Table -->
    <div class="table-wrapper">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <span class="font-medium text-slate-800">#<?php echo e($user->id); ?></span>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar avatar-sm">
                                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800"><?php echo e($user->name); ?></p>
                                        <?php if($user->username): ?>
                                            <p class="text-xs text-slate-500"><?php echo e('@' . $user->username); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($user->email); ?></td>
                            <td>
                                <?php if($user->role == 'admin'): ?>
                                    <span class="badge-success">Admin</span>
                                <?php else: ?>
                                    <span class="badge-primary">User</span>
                                <?php endif; ?>
                            </td>
                            <td class="whitespace-nowrap"><?php echo e($user->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <div class="flex items-center justify-center gap-1">
                                    <a href="<?php echo e(route('users.show', $user)); ?>"
                                        class="btn-icon text-slate-500 hover:text-primary-600 hover:bg-primary-50"
                                        title="Lihat">
                                        <span class="iconify w-4 h-4" data-icon="heroicons:eye"></span>
                                    </a>
                                    <a href="<?php echo e(route('users.edit', $user)); ?>"
                                        class="btn-icon text-slate-500 hover:text-amber-600 hover:bg-amber-50" title="Edit">
                                        <span class="iconify w-4 h-4" data-icon="heroicons:pencil-square"></span>
                                    </a>
                                    <?php if($user->id !== auth()->id()): ?>
                                        <form action="<?php echo e(route('users.destroy', $user)); ?>" method="POST" class="inline"
                                            onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn-icon text-slate-500 hover:text-red-600 hover:bg-red-50"
                                                title="Hapus">
                                                <span class="iconify w-4 h-4" data-icon="heroicons:trash"></span>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <span class="iconify w-12 h-12 mx-auto mb-3 text-slate-300"
                                        data-icon="heroicons:users"></span>
                                    <p>Belum ada pengguna</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($users->hasPages()): ?>
            <div class="px-5 py-4 border-t border-slate-100">
                <?php echo e($users->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\manajemen_kas\resources\views/users/index.blade.php ENDPATH**/ ?>