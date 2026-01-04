<?php

namespace App\Http\Controllers;

use App\Models\CashTransaction;
use App\Models\Receipt;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CashTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = CashTransaction::with(['user', 'approver']);

        // Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:masuk,keluar',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'transaction_date' => 'required|date',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $transaction = CashTransaction::create([
            'user_id' => auth()->id(),
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'transaction_date' => $validated['transaction_date'],
            'status' => 'pending',
        ]);

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $path = $file->store('receipts', 'public');

            Receipt::create([
                'transaction_id' => $transaction->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
            ]);
        }

        ActivityLog::log('create_transaction', 'Created new ' . $validated['type'] . ' transaction', $transaction);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dibuat dan menunggu persetujuan.');
    }

    public function show(CashTransaction $transaction)
    {
        $transaction->load(['user', 'approver', 'receipts']);
        return view('transactions.show', compact('transaction'));
    }

    public function edit(CashTransaction $transaction)
    {
        // Only allow editing pending or rejected transactions
        if ($transaction->isApproved()) {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'Transaksi yang sudah disetujui tidak dapat diedit.');
        }

        $transaction->load('receipts');
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, CashTransaction $transaction)
    {
        if ($transaction->isApproved()) {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'Transaksi yang sudah disetujui tidak dapat diedit.');
        }

        $validated = $request->validate([
            'type' => 'required|in:masuk,keluar',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'transaction_date' => 'required|date',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $oldValues = $transaction->toArray();

        $transaction->update([
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'transaction_date' => $validated['transaction_date'],
            'status' => 'pending', // Reset to pending after edit
            'rejection_note' => null,
        ]);

        // Handle new receipt upload
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $path = $file->store('receipts', 'public');

            Receipt::create([
                'transaction_id' => $transaction->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
            ]);
        }

        ActivityLog::log('update_transaction', 'Updated transaction', $transaction, $oldValues, $transaction->toArray());

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(CashTransaction $transaction)
    {
        if ($transaction->isApproved()) {
            return redirect()->route('transactions.index')
                ->with('error', 'Transaksi yang sudah disetujui tidak dapat dihapus.');
        }

        // Delete associated receipts
        foreach ($transaction->receipts as $receipt) {
            Storage::disk('public')->delete($receipt->file_path);
            $receipt->delete();
        }

        ActivityLog::log('delete_transaction', 'Deleted transaction', $transaction);

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    public function approve(CashTransaction $transaction)
    {
        if (!auth()->user()->canApproveTransaction()) {
            abort(403, 'Anda tidak memiliki izin untuk menyetujui transaksi.');
        }

        if (!$transaction->isPending()) {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'Transaksi ini tidak dalam status pending.');
        }

        $transaction->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        ActivityLog::log('approve_transaction', 'Approved transaction', $transaction);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaksi berhasil disetujui.');
    }

    public function reject(Request $request, CashTransaction $transaction)
    {
        if (!auth()->user()->canApproveTransaction()) {
            abort(403, 'Anda tidak memiliki izin untuk menolak transaksi.');
        }

        if (!$transaction->isPending()) {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'Transaksi ini tidak dalam status pending.');
        }

        $validated = $request->validate([
            'rejection_note' => 'required|string|max:500',
        ]);

        $transaction->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_note' => $validated['rejection_note'],
        ]);

        ActivityLog::log('reject_transaction', 'Rejected transaction: ' . $validated['rejection_note'], $transaction);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaksi berhasil ditolak.');
    }

    public function pending()
    {
        $transactions = CashTransaction::with(['user'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('transactions.pending', compact('transactions'));
    }
}
