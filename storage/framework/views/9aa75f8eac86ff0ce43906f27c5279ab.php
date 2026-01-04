<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title ?? 'Sistem Manajemen Uang Kas'); ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />

    <!-- Heroicons CDN for icons -->
    <script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@3.0.0/dist/iconify.min.js"></script>

    <!-- Vite Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="bg-slate-50 min-h-screen">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden"
            @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-slate-900 to-slate-950 z-50 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:flex lg:flex-col"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <!-- Logo -->
            <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-800">
                <div
                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-cyan-500 flex items-center justify-center">
                    <span class="iconify w-6 h-6 text-white" data-icon="heroicons:banknotes"></span>
                </div>
                <div>
                    <h1 class="font-bold text-lg text-gradient">SIMKAS</h1>
                    <p class="text-[10px] text-slate-500 uppercase tracking-wider">Manajemen Kas</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 py-4 overflow-y-auto">
                <a href="<?php echo e(route('dashboard')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                    <span class="iconify w-5 h-5" data-icon="heroicons:squares-2x2"></span>
                    <span>Dashboard</span>
                </a>

                <div class="nav-section">Transaksi</div>
                <a href="<?php echo e(route('transactions.index')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('transactions.index') ? 'active' : ''); ?>">
                    <span class="iconify w-5 h-5" data-icon="heroicons:document-text"></span>
                    <span>Semua Transaksi</span>
                </a>
                <a href="<?php echo e(route('transactions.create')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('transactions.create') ? 'active' : ''); ?>">
                    <span class="iconify w-5 h-5" data-icon="heroicons:plus-circle"></span>
                    <span>Transaksi Baru</span>
                </a>
                <?php if(auth()->user()->canApproveTransaction()): ?>
                    <a href="<?php echo e(route('transactions.pending')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('transactions.pending') ? 'active' : ''); ?>">
                        <span class="iconify w-5 h-5" data-icon="heroicons:clock"></span>
                        <span>Menunggu Validasi</span>
                    </a>
                <?php endif; ?>

                <div class="nav-section">Kas</div>
                <a href="<?php echo e(route('petty-cash.index')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('petty-cash.*') ? 'active' : ''); ?>">
                    <span class="iconify w-5 h-5" data-icon="heroicons:wallet"></span>
                    <span>Kas Kecil</span>
                </a>
                <?php if(auth()->user()->canApproveTransaction()): ?>
                    <a href="<?php echo e(route('reconciliations.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('reconciliations.*') ? 'active' : ''); ?>">
                        <span class="iconify w-5 h-5" data-icon="heroicons:arrows-right-left"></span>
                        <span>Rekonsiliasi</span>
                    </a>
                <?php endif; ?>

                <div class="nav-section">Laporan</div>
                <a href="<?php echo e(route('reports.index')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
                    <span class="iconify w-5 h-5" data-icon="heroicons:chart-bar"></span>
                    <span>Laporan Kas</span>
                </a>

                <?php if(auth()->user()->canManageUsers()): ?>
                    <div class="nav-section">Administrasi</div>
                    <a href="<?php echo e(route('users.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
                        <span class="iconify w-5 h-5" data-icon="heroicons:users"></span>
                        <span>Pengguna</span>
                    </a>
                    <a href="<?php echo e(route('activity-logs')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('activity-logs') ? 'active' : ''); ?>">
                        <span class="iconify w-5 h-5" data-icon="heroicons:clipboard-document-list"></span>
                        <span>Log Aktivitas</span>
                    </a>
                <?php endif; ?>
            </nav>

            <!-- User Info at Bottom -->
            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="avatar avatar-sm">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-xs text-slate-500 capitalize"><?php echo e(auth()->user()->role); ?></p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Header -->
            <header
                class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-6 sticky top-0 z-30">
                <!-- Mobile Menu Button -->
                <button @click="sidebarOpen = true"
                    class="lg:hidden p-2 -ml-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors">
                    <span class="iconify w-6 h-6" data-icon="heroicons:bars-3"></span>
                </button>

                <!-- Page Title -->
                <h2 class="text-lg font-semibold text-slate-800 hidden lg:block"><?php echo e($title ?? 'Dashboard'); ?></h2>

                <!-- Right Side -->
                <div class="flex items-center gap-3">
                    <!-- Notifications Dropdown -->
                    <div class="relative" x-data="{ notifOpen: false }">
                        <button @click="notifOpen = !notifOpen"
                            class="relative p-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors">
                            <span class="iconify w-5 h-5" data-icon="heroicons:bell"></span>
                            <?php if($notificationCount > 0): ?>
                                <span class="absolute top-1 right-1 min-w-[18px] h-[18px] px-1 bg-red-500 rounded-full text-[10px] font-bold text-white flex items-center justify-center">
                                    <?php echo e($notificationCount > 99 ? '99+' : $notificationCount); ?>

                                </span>
                            <?php endif; ?>
                        </button>

                        <!-- Notification Dropdown Menu -->
                        <div x-show="notifOpen" @click.outside="notifOpen = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-lg border border-slate-200 py-1 z-50">
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="font-semibold text-slate-800">Notifikasi</p>
                            </div>
                            
                            <?php if($notificationCount > 0): ?>
                                <div class="max-h-64 overflow-y-auto">
                                    <?php if($pendingTransactionsCount > 0): ?>
                                        <a href="<?php echo e(route('transactions.pending')); ?>" 
                                            class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors">
                                            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                                                <span class="iconify w-5 h-5 text-amber-600" data-icon="heroicons:clock"></span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-800">Transaksi Pending</p>
                                                <p class="text-xs text-slate-500"><?php echo e($pendingTransactionsCount); ?> transaksi menunggu validasi</p>
                                            </div>
                                            <span class="badge-warning text-xs"><?php echo e($pendingTransactionsCount); ?></span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if($pendingReconciliationsCount > 0): ?>
                                        <a href="<?php echo e(route('reconciliations.index')); ?>" 
                                            class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors">
                                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                                                <span class="iconify w-5 h-5 text-blue-600" data-icon="heroicons:arrows-right-left"></span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-800">Rekonsiliasi Pending</p>
                                                <p class="text-xs text-slate-500"><?php echo e($pendingReconciliationsCount); ?> rekonsiliasi menunggu</p>
                                            </div>
                                            <span class="badge-primary text-xs"><?php echo e($pendingReconciliationsCount); ?></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="border-t border-slate-100 px-4 py-2">
                                    <a href="<?php echo e(route('transactions.pending')); ?>" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                                        Lihat semua →
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="px-4 py-8 text-center">
                                    <span class="iconify w-10 h-10 text-slate-300 mx-auto mb-2" data-icon="heroicons:bell-slash"></span>
                                    <p class="text-sm text-slate-500">Tidak ada notifikasi</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-2 p-1.5 pr-3 rounded-lg hover:bg-slate-100 transition-colors">
                            <div class="avatar avatar-sm">
                                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                            </div>
                            <span
                                class="text-sm font-medium text-slate-700 hidden sm:block"><?php echo e(auth()->user()->name); ?></span>
                            <span class="iconify w-4 h-4 text-slate-400" data-icon="heroicons:chevron-down"></span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 py-1 z-50">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-sm font-medium text-slate-800"><?php echo e(auth()->user()->name); ?></p>
                                <p class="text-xs text-slate-500"><?php echo e(auth()->user()->email); ?></p>
                            </div>
                            <a href="#"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                <span class="iconify w-4 h-4" data-icon="heroicons:user"></span>
                                Profil
                            </a>
                            <form action="<?php echo e(route('logout')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit"
                                    class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <span class="iconify w-4 h-4" data-icon="heroicons:arrow-right-on-rectangle"></span>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-6">
                <!-- Alerts -->
                <?php if(session('success')): ?>
                    <div class="alert-success mb-4" x-data="{ show: true }" x-show="show">
                        <span class="iconify w-5 h-5 text-emerald-500 shrink-0" data-icon="heroicons:check-circle"></span>
                        <span class="flex-1"><?php echo e(session('success')); ?></span>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                            <span class="iconify w-4 h-4" data-icon="heroicons:x-mark"></span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert-danger mb-4" x-data="{ show: true }" x-show="show">
                        <span class="iconify w-5 h-5 text-red-500 shrink-0" data-icon="heroicons:exclamation-circle"></span>
                        <span class="flex-1"><?php echo e(session('error')); ?></span>
                        <button @click="show = false" class="text-red-500 hover:text-red-700">
                            <span class="iconify w-4 h-4" data-icon="heroicons:x-mark"></span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert-danger mb-4">
                        <span class="iconify w-5 h-5 text-red-500 shrink-0"
                            data-icon="heroicons:exclamation-triangle"></span>
                        <div class="flex-1">
                            <p class="font-medium">Terjadi kesalahan:</p>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH C:\xampp\htdocs\manajemen_kas\resources\views/layouts/app.blade.php ENDPATH**/ ?>