@extends('layouts.app')

@section('content')
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
                    @forelse($logs as $log)
                        <tr>
                            <td class="whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="avatar avatar-sm">
                                        {{ strtoupper(substr($log->user->name ?? 'X', 0, 1)) }}
                                    </div>
                                    <span class="text-slate-700">{{ $log->user->name ?? 'Deleted User' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-secondary">{{ $log->action }}</span>
                            </td>
                            <td class="max-w-xs truncate">{{ Str::limit($log->description, 60) ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <span class="iconify w-12 h-12 mx-auto mb-3 text-slate-300"
                                        data-icon="heroicons:clipboard-document-list"></span>
                                    <p>Belum ada aktivitas</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
@endsection