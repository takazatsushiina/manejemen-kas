@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800">Transaksi Menunggu Validasi</h1>
        <p class="text-sm text-slate-500">{{ $transactions->total() }} transaksi perlu divalidasi</p>
    </div>

    <!-- Pending Transactions Table -->
    <div class="table-wrapper">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Dibuat Oleh</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr>
                            <td>
                                <span class="font-medium text-slate-800">#{{ $tx->id }}</span>
                            </td>
                            <td class="whitespace-nowrap">{{ $tx->transaction_date->format('d/m/Y') }}</td>
                            <td class="max-w-xs truncate">{{ Str::limit($tx->description, 40) ?: '-' }}</td>
                            <td>
                                @if($tx->isMasuk())
                                    <span class="badge-success">
                                        <span class="iconify w-3 h-3 mr-1" data-icon="heroicons:arrow-down"></span>
                                        Masuk
                                    </span>
                                @else
                                    <span class="badge-danger">
                                        <span class="iconify w-3 h-3 mr-1" data-icon="heroicons:arrow-up"></span>
                                        Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="font-bold {{ $tx->isMasuk() ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $tx->formatted_amount }}
                            </td>
                            <td class="whitespace-nowrap">{{ $tx->user->name }}</td>
                            <td class="text-center">
                                <a href="{{ route('transactions.show', $tx) }}" class="btn-sm btn-primary">
                                    <span class="iconify w-4 h-4" data-icon="heroicons:eye"></span>
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="text-center py-12">
                                    <span class="iconify w-16 h-16 mx-auto mb-4 text-emerald-300"
                                        data-icon="heroicons:check-circle"></span>
                                    <p class="text-lg font-medium text-slate-700 mb-1">Semua transaksi sudah divalidasi!</p>
                                    <p class="text-sm text-slate-500">Tidak ada transaksi yang menunggu validasi saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
@endsection