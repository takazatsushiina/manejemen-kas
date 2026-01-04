@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800">Laporan Arus Kas</h1>
        <p class="text-sm text-slate-500">Generate dan export laporan keuangan</p>
    </div>

    <!-- Filter & Export Row -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <!-- Filter Form -->
        <div class="lg:col-span-3 card">
            <div class="card-body">
                <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[150px]">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-input" value="{{ $startDate }}">
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-input" value="{{ $endDate }}">
                    </div>
                    <button type="submit" class="btn-primary">
                        <span class="iconify w-5 h-5" data-icon="heroicons:funnel"></span>
                        Tampilkan
                    </button>
                </form>
            </div>
        </div>

        <!-- Export Form -->
        <div class="card">
            <div class="card-body">
                <p class="text-sm font-medium text-slate-600 mb-3">Export Laporan</p>
                <form action="{{ route('reports.export') }}" method="POST" target="_blank" class="space-y-3">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <div class="grid grid-cols-2 gap-2">
                        <select name="type" class="form-select text-sm">
                            <option value="harian">Harian</option>
                            <option value="mingguan">Mingguan</option>
                            <option value="bulanan" selected>Bulanan</option>
                        </select>
                        <select name="format" class="form-select text-sm">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-success w-full justify-center">
                        <span class="iconify w-5 h-5" data-icon="heroicons:arrow-down-tray"></span>
                        Export
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="stat-card text-center">
            <div
                class="w-12 h-12 mx-auto mb-3 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                <span class="iconify w-6 h-6 text-white" data-icon="heroicons:arrow-down-circle"></span>
            </div>
            <p class="text-sm text-slate-500">Total Kas Masuk</p>
            <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
        </div>

        <div class="stat-card text-center">
            <div
                class="w-12 h-12 mx-auto mb-3 rounded-xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center">
                <span class="iconify w-6 h-6 text-white" data-icon="heroicons:arrow-up-circle"></span>
            </div>
            <p class="text-sm text-slate-500">Total Kas Keluar</p>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
        </div>

        <div class="stat-card text-center">
            <div
                class="w-12 h-12 mx-auto mb-3 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                <span class="iconify w-6 h-6 text-white" data-icon="heroicons:chart-bar"></span>
            </div>
            <p class="text-sm text-slate-500">Arus Kas Bersih</p>
            <p class="text-2xl font-bold {{ $netCashFlow >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                {{ $netCashFlow >= 0 ? '+' : '' }}Rp {{ number_format($netCashFlow, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Daily Summary & Previous Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Daily Summary -->
        <div class="lg:col-span-2 table-wrapper">
            <div class="card-header flex items-center gap-2">
                <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:calendar-days"></span>
                Ringkasan per Hari
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th class="text-right">Masuk</th>
                            <th class="text-right">Keluar</th>
                            <th class="text-right">Saldo</th>
                            <th class="text-center">Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dailySummary as $date => $summary)
                            <tr>
                                <td class="whitespace-nowrap">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                <td class="text-right text-emerald-600">+Rp {{ number_format($summary['masuk'], 0, ',', '.') }}
                                </td>
                                <td class="text-right text-red-600">-Rp {{ number_format($summary['keluar'], 0, ',', '.') }}
                                </td>
                                <td
                                    class="text-right font-bold {{ ($summary['masuk'] - $summary['keluar']) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                    Rp {{ number_format($summary['masuk'] - $summary['keluar'], 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge-secondary">{{ $summary['count'] }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state py-8">
                                        <p>Tidak ada data untuk periode ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Previous Reports -->
        <div class="card">
            <div class="card-header flex items-center gap-2">
                <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:document-text"></span>
                Laporan Terakhir
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($previousReports as $report)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-slate-800 text-sm">{{ $report->type_name }}</p>
                            <p class="text-xs text-slate-500">{{ $report->start_date->format('d/m/Y') }} -
                                {{ $report->end_date->format('d/m/Y') }}</p>
                        </div>
                        <span class="badge {{ $report->file_format == 'pdf' ? 'badge-danger' : 'badge-success' }}">
                            {{ strtoupper($report->file_format) }}
                        </span>
                    </div>
                @empty
                    <div class="p-5 text-center text-slate-500 text-sm">
                        Belum ada laporan dibuat
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Transactions Detail -->
    <div class="table-wrapper">
        <div class="card-header flex items-center gap-2">
            <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:queue-list"></span>
            Detail Transaksi ({{ $startDate }} s/d {{ $endDate }})
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Tipe</th>
                        <th class="text-right">Nominal</th>
                        <th>Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr>
                            <td class="whitespace-nowrap">{{ $tx->transaction_date->format('d/m/Y') }}</td>
                            <td class="max-w-xs truncate">{{ Str::limit($tx->description, 50) ?: '-' }}</td>
                            <td>
                                @if($tx->isMasuk())
                                    <span class="badge-success">Masuk</span>
                                @else
                                    <span class="badge-danger">Keluar</span>
                                @endif
                            </td>
                            <td class="text-right font-bold {{ $tx->isMasuk() ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $tx->isMasuk() ? '+' : '-' }}{{ $tx->formatted_amount }}
                            </td>
                            <td class="whitespace-nowrap">{{ $tx->user->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state py-8">
                                    <p>Tidak ada transaksi untuk periode ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection