@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Transaction Details Card -->
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:document-text"></span>
                        Detail Transaksi #{{ $transaction->id }}
                    </div>
                    <div class="flex items-center gap-2">
                        @if($transaction->isPending() || $transaction->isRejected())
                            <a href="{{ route('transactions.edit', $transaction) }}" class="btn-sm btn-warning">
                                <span class="iconify w-4 h-4" data-icon="heroicons:pencil-square"></span>
                                Edit
                            </a>
                        @endif
                        <a href="{{ route('transactions.index') }}" class="btn-sm btn-secondary">
                            <span class="iconify w-4 h-4" data-icon="heroicons:arrow-left"></span>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Tipe</p>
                                @if($transaction->isMasuk())
                                    <span class="badge-success text-sm px-3 py-1.5">
                                        <span class="iconify w-4 h-4 mr-1" data-icon="heroicons:arrow-down"></span>
                                        Kas Masuk
                                    </span>
                                @else
                                    <span class="badge-danger text-sm px-3 py-1.5">
                                        <span class="iconify w-4 h-4 mr-1" data-icon="heroicons:arrow-up"></span>
                                        Kas Keluar
                                    </span>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Nominal</p>
                                <p
                                    class="text-2xl font-bold {{ $transaction->isMasuk() ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $transaction->formatted_amount }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Tanggal</p>
                                <p class="font-medium text-slate-800">{{ $transaction->transaction_date->format('d F Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Status</p>
                                @if($transaction->isPending())
                                    <span class="badge-warning">Pending</span>
                                @elseif($transaction->isApproved())
                                    <span class="badge-success">Approved</span>
                                @else
                                    <span class="badge-danger">Rejected</span>
                                @endif
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Dibuat Oleh</p>
                                <p class="font-medium text-slate-800">{{ $transaction->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Dibuat Pada</p>
                                <p class="font-medium text-slate-800">{{ $transaction->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            @if($transaction->approver)
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Diproses Oleh</p>
                                    <p class="font-medium text-slate-800">{{ $transaction->approver->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Diproses Pada</p>
                                    <p class="font-medium text-slate-800">{{ $transaction->approved_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    @if($transaction->description)
                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <p class="text-sm text-slate-500 mb-2">Deskripsi</p>
                            <p class="text-slate-700">{{ $transaction->description }}</p>
                        </div>
                    @endif

                    <!-- Rejection Note -->
                    @if($transaction->isRejected() && $transaction->rejection_note)
                        <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <span class="iconify w-5 h-5 text-red-500 shrink-0"
                                    data-icon="heroicons:exclamation-triangle"></span>
                                <div>
                                    <p class="font-medium text-red-800 mb-1">Alasan Penolakan</p>
                                    <p class="text-sm text-red-700">{{ $transaction->rejection_note }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Receipts Card -->
            @if($transaction->receipts->count() > 0)
                <div class="card">
                    <div class="card-header flex items-center gap-2">
                        <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:paper-clip"></span>
                        Bukti Transaksi
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($transaction->receipts as $receipt)
                                <div class="border border-slate-200 rounded-xl overflow-hidden">
                                    @if(Str::contains($receipt->file_type, 'image'))
                                        <img src="{{ $receipt->file_url }}" alt="Receipt" class="w-full h-32 object-cover">
                                    @else
                                        <div class="w-full h-32 bg-slate-50 flex items-center justify-center">
                                            <span class="iconify w-12 h-12 text-red-500" data-icon="heroicons:document"></span>
                                        </div>
                                    @endif
                                    <div class="p-3">
                                        <p class="text-sm font-medium text-slate-700 truncate mb-2">{{ $receipt->file_name }}</p>
                                        <a href="{{ $receipt->file_url }}" target="_blank"
                                            class="btn-sm btn-outline-primary w-full justify-center">
                                            <span class="iconify w-4 h-4" data-icon="heroicons:arrow-down-tray"></span>
                                            Unduh
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar - Approval Actions -->
        <div class="lg:col-span-1">
            @if(auth()->user()->canApproveTransaction() && $transaction->isPending())
                <div class="card sticky top-20">
                    <div class="card-header flex items-center gap-2">
                        <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:shield-check"></span>
                        Validasi Transaksi
                    </div>
                    <div class="card-body space-y-4">
                        <!-- Approve Button -->
                        <form action="{{ route('transactions.approve', $transaction) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-success w-full justify-center">
                                <span class="iconify w-5 h-5" data-icon="heroicons:check-circle"></span>
                                Setujui Transaksi
                            </button>
                        </form>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-slate-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-slate-500">atau</span>
                            </div>
                        </div>

                        <!-- Reject Form -->
                        <form action="{{ route('transactions.reject', $transaction) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label for="rejection_note" class="form-label">Alasan Penolakan</label>
                                <textarea id="rejection_note" name="rejection_note" rows="3" class="form-input resize-none"
                                    placeholder="Jelaskan alasan penolakan..." required></textarea>
                            </div>
                            <button type="submit" class="btn-danger w-full justify-center">
                                <span class="iconify w-5 h-5" data-icon="heroicons:x-circle"></span>
                                Tolak Transaksi
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection