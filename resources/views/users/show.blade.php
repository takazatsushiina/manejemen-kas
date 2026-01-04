@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Profile Card -->
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:user"></span>
                        Detail Pengguna
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('users.edit', $user) }}" class="btn-sm btn-warning">
                            <span class="iconify w-4 h-4" data-icon="heroicons:pencil-square"></span>
                            Edit
                        </a>
                        <a href="{{ route('users.index') }}" class="btn-sm btn-secondary">
                            <span class="iconify w-4 h-4" data-icon="heroicons:arrow-left"></span>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- User Info -->
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-20 h-20 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white text-3xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800">{{ $user->name }}</h3>
                            <p class="text-slate-500">{{ $user->email }}</p>
                            @if($user->role == 'admin')
                                <span class="badge-success mt-1">Admin</span>
                            @else
                                <span class="badge-primary mt-1">User</span>
                            @endif
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-100 pt-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Username</p>
                                <p class="font-medium text-slate-800">{{ $user->username ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Terdaftar</p>
                                <p class="font-medium text-slate-800">{{ $user->created_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Total Transaksi</p>
                                <p class="font-medium text-slate-800">{{ $user->transactions->count() }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Terakhir Aktif</p>
                                <p class="font-medium text-slate-800">{{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Logs -->
            <div class="table-wrapper">
                <div class="card-header flex items-center gap-2">
                    <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:clipboard-document-list"></span>
                    Log Aktivitas Terbaru
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Aksi</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->activityLogs as $log)
                                <tr>
                                    <td class="whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td><span class="badge-secondary">{{ $log->action }}</span></td>
                                    <td>{{ $log->description ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-state py-8">
                                            <p>Belum ada aktivitas</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div class="lg:col-span-1 space-y-6">
            <div class="stat-card text-center">
                <div
                    class="w-12 h-12 mx-auto mb-3 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                    <span class="iconify w-6 h-6 text-white" data-icon="heroicons:arrow-down-circle"></span>
                </div>
                <p class="text-sm text-slate-500">Total Kas Masuk</p>
                <p class="text-xl font-bold text-emerald-600">
                    Rp
                    {{ number_format($user->transactions->where('type', 'masuk')->where('status', 'approved')->sum('amount'), 0, ',', '.') }}
                </p>
            </div>

            <div class="stat-card text-center">
                <div
                    class="w-12 h-12 mx-auto mb-3 rounded-xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center">
                    <span class="iconify w-6 h-6 text-white" data-icon="heroicons:arrow-up-circle"></span>
                </div>
                <p class="text-sm text-slate-500">Total Kas Keluar</p>
                <p class="text-xl font-bold text-red-600">
                    Rp
                    {{ number_format($user->transactions->where('type', 'keluar')->where('status', 'approved')->sum('amount'), 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
@endsection