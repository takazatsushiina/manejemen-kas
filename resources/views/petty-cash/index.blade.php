@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800">Kas Kecil (Petty Cash)</h1>
        <p class="text-sm text-slate-500">Kelola saldo kas kecil untuk pengeluaran rutin</p>
    </div>

    <!-- Stats & Forms Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Current Balance -->
        <div class="stat-card text-center">
            <div
                class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                <span class="iconify w-8 h-8 text-white" data-icon="heroicons:wallet"></span>
            </div>
            <p class="text-sm text-slate-500 mb-1">Saldo Kas Kecil Saat Ini</p>
            <p class="text-3xl font-bold text-slate-800">Rp
                {{ number_format($pettyCash->current_balance ?? 0, 0, ',', '.') }}</p>
            @if($pettyCash)
                <p class="text-xs text-slate-400 mt-2">Terakhir update: {{ $pettyCash->updated_at->diffForHumans() }}</p>
            @endif
        </div>

        <!-- Top Up Form -->
        <div class="card">
            <div class="px-5 py-3 border-b border-emerald-100 bg-emerald-50">
                <h3 class="font-semibold text-emerald-700 flex items-center gap-2">
                    <span class="iconify w-5 h-5" data-icon="heroicons:plus-circle"></span>
                    Top Up Kas Kecil
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('petty-cash.topup') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="topup_amount" class="form-label">Nominal</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-medium">Rp</span>
                            <input type="number" id="topup_amount" name="amount" class="form-input pl-12" min="1000"
                                step="1000" required placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label for="topup_notes" class="form-label">Catatan</label>
                        <input type="text" id="topup_notes" name="notes" class="form-input"
                            placeholder="Keterangan top up...">
                    </div>
                    <button type="submit" class="btn-success w-full justify-center">
                        <span class="iconify w-5 h-5" data-icon="heroicons:arrow-down-circle"></span>
                        Top Up
                    </button>
                </form>
            </div>
        </div>

        <!-- Withdraw Form -->
        <div class="card">
            <div class="px-5 py-3 border-b border-red-100 bg-red-50">
                <h3 class="font-semibold text-red-700 flex items-center gap-2">
                    <span class="iconify w-5 h-5" data-icon="heroicons:minus-circle"></span>
                    Pengeluaran Kas Kecil
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('petty-cash.withdraw') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="withdraw_amount" class="form-label">Nominal</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-medium">Rp</span>
                            <input type="number" id="withdraw_amount" name="amount" class="form-input pl-12" min="1000"
                                max="{{ $pettyCash->current_balance ?? 0 }}" step="1000" required placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label for="withdraw_notes" class="form-label">Keterangan <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="withdraw_notes" name="notes" class="form-input" required
                            placeholder="Untuk keperluan...">
                    </div>
                    <button type="submit" class="btn-danger w-full justify-center">
                        <span class="iconify w-5 h-5" data-icon="heroicons:arrow-up-circle"></span>
                        Keluarkan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="table-wrapper">
        <div class="card-header flex items-center gap-2">
            <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:clock"></span>
            Riwayat Kas Kecil
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr>
                            <td class="whitespace-nowrap">{{ $tx->transaction_date->format('d/m/Y') }}</td>
                            <td>{{ $tx->description }}</td>
                            <td>
                                @if($tx->isMasuk())
                                    <span class="badge-success">Top Up</span>
                                @else
                                    <span class="badge-danger">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="font-bold {{ $tx->isMasuk() ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $tx->isMasuk() ? '+' : '-' }}{{ $tx->formatted_amount }}
                            </td>
                            <td class="whitespace-nowrap">{{ $tx->user->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <span class="iconify w-12 h-12 mx-auto mb-3 text-slate-300"
                                        data-icon="heroicons:inbox"></span>
                                    <p>Belum ada riwayat kas kecil</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection