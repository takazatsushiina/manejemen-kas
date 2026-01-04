@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Rekonsiliasi Kas-Bank</h1>
            <p class="text-sm text-slate-500">Pencocokan saldo kas dengan rekening bank</p>
        </div>
        <a href="{{ route('reconciliations.create') }}" class="btn-primary">
            <span class="iconify w-5 h-5" data-icon="heroicons:plus-circle"></span>
            Buat Rekonsiliasi
        </a>
    </div>

    <!-- Reconciliations Table -->
    <div class="table-wrapper">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th class="text-right">Saldo Bank</th>
                        <th class="text-right">Saldo Buku Kas</th>
                        <th class="text-right">Selisih</th>
                        <th>Dibuat Oleh</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reconciliations as $recon)
                        <tr>
                            <td>
                                <span class="font-medium text-slate-800">#{{ $recon->id }}</span>
                            </td>
                            <td class="whitespace-nowrap">{{ $recon->reconciliation_date->format('d/m/Y') }}</td>
                            <td class="text-right font-medium">{{ $recon->formatted_bank_balance }}</td>
                            <td class="text-right">{{ $recon->formatted_cash_book_balance }}</td>
                            <td
                                class="text-right font-bold {{ $recon->difference == 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $recon->formatted_difference }}
                            </td>
                            <td class="whitespace-nowrap">{{ $recon->user->name }}</td>
                            <td class="text-center">
                                <a href="{{ route('reconciliations.show', $recon) }}"
                                    class="btn-icon text-slate-500 hover:text-primary-600 hover:bg-primary-50">
                                    <span class="iconify w-4 h-4" data-icon="heroicons:eye"></span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <span class="iconify w-12 h-12 mx-auto mb-3 text-slate-300"
                                        data-icon="heroicons:arrows-right-left"></span>
                                    <p>Belum ada rekonsiliasi</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reconciliations->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $reconciliations->links() }}
            </div>
        @endif
    </div>
@endsection