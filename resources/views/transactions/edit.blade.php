@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="card">
            <div class="card-header flex items-center gap-2">
                <span class="iconify w-5 h-5 text-slate-400" data-icon="heroicons:pencil-square"></span>
                Edit Transaksi #{{ $transaction->id }}
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.update', $transaction) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Type & Date Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Transaction Type -->
                        <div>
                            <label class="form-label">Tipe Transaksi <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3" x-data="{ type: '{{ old('type', $transaction->type) }}' }">
                                <label
                                    class="relative flex items-center justify-center gap-2 px-4 py-3 rounded-lg border-2 cursor-pointer transition-all"
                                    :class="type === 'masuk' ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-200 hover:border-slate-300'">
                                    <input type="radio" name="type" value="masuk" class="sr-only" x-model="type">
                                    <span class="iconify w-5 h-5" data-icon="heroicons:arrow-down-circle"></span>
                                    <span class="font-medium">Kas Masuk</span>
                                </label>
                                <label
                                    class="relative flex items-center justify-center gap-2 px-4 py-3 rounded-lg border-2 cursor-pointer transition-all"
                                    :class="type === 'keluar' ? 'border-red-500 bg-red-50 text-red-700' : 'border-slate-200 hover:border-slate-300'">
                                    <input type="radio" name="type" value="keluar" class="sr-only" x-model="type">
                                    <span class="iconify w-5 h-5" data-icon="heroicons:arrow-up-circle"></span>
                                    <span class="font-medium">Kas Keluar</span>
                                </label>
                            </div>
                        </div>

                        <!-- Date -->
                        <div>
                            <label for="transaction_date" class="form-label">Tanggal <span
                                    class="text-red-500">*</span></label>
                            <input type="date" id="transaction_date" name="transaction_date" class="form-input"
                                value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}"
                                required>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="form-label">Nominal <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-medium">Rp</span>
                            <input type="number" id="amount" name="amount"
                                class="form-input pl-12 text-right font-semibold text-lg"
                                value="{{ old('amount', $transaction->amount) }}" min="0" step="1000" required>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" class="form-input resize-none"
                            placeholder="Keterangan transaksi...">{{ old('description', $transaction->description) }}</textarea>
                    </div>

                    <!-- Existing Receipts -->
                    @if($transaction->receipts->count() > 0)
                        <div>
                            <label class="form-label">Bukti yang Sudah Ada</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($transaction->receipts as $receipt)
                                    <a href="{{ $receipt->file_url }}" target="_blank"
                                        class="inline-flex items-center gap-2 px-3 py-2 bg-slate-100 rounded-lg text-sm text-slate-700 hover:bg-slate-200 transition-colors">
                                        <span class="iconify w-4 h-4" data-icon="heroicons:paper-clip"></span>
                                        {{ $receipt->file_name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Receipt Upload -->
                    <div>
                        <label for="receipt" class="form-label">Tambah Bukti Transaksi</label>
                        <div class="relative" x-data="{ fileName: '' }">
                            <input type="file" id="receipt" name="receipt" accept=".jpg,.jpeg,.png,.pdf" class="hidden"
                                @change="fileName = $event.target.files[0]?.name || ''">
                            <label for="receipt"
                                class="flex items-center justify-center gap-3 px-4 py-6 border-2 border-dashed border-slate-200 rounded-lg cursor-pointer hover:border-primary-400 hover:bg-primary-50/50 transition-colors">
                                <span class="iconify w-6 h-6 text-slate-400" data-icon="heroicons:cloud-arrow-up"></span>
                                <div class="text-center">
                                    <p class="text-sm font-medium text-slate-700"
                                        x-text="fileName || 'Klik untuk upload file'"></p>
                                    <p class="text-xs text-slate-500">Format: JPG, PNG, PDF. Maksimal 5MB</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <button type="submit" class="btn-primary">
                            <span class="iconify w-5 h-5" data-icon="heroicons:check-circle"></span>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('transactions.show', $transaction) }}" class="btn-secondary">
                            <span class="iconify w-5 h-5" data-icon="heroicons:x-mark"></span>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection