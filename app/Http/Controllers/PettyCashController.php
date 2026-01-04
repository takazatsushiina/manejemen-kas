<?php

namespace App\Http\Controllers;

use App\Models\PettyCash;
use App\Models\CashTransaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PettyCashController extends Controller
{
    public function index()
    {
        $pettyCash = PettyCash::first();

        // Get petty cash related transactions
        $transactions = CashTransaction::with('user')
            ->where('description', 'like', '%kas kecil%')
            ->orWhere('description', 'like', '%petty cash%')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('petty-cash.index', compact('pettyCash', 'transactions'));
    }

    public function topup(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000',
            'notes' => 'nullable|string|max:500',
        ]);

        $pettyCash = PettyCash::firstOrCreate([], [
            'current_balance' => 0,
            'initial_balance' => 0,
        ]);

        $oldBalance = $pettyCash->current_balance;
        $pettyCash->current_balance += $validated['amount'];
        $pettyCash->notes = $validated['notes'] ?? 'Top up kas kecil';
        $pettyCash->save();

        // Create a transaction record
        $transaction = CashTransaction::create([
            'user_id' => auth()->id(),
            'type' => 'masuk',
            'amount' => $validated['amount'],
            'description' => 'Top up kas kecil: ' . ($validated['notes'] ?? ''),
            'transaction_date' => now()->toDateString(),
            'status' => 'approved', // Auto approved for petty cash
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        ActivityLog::log(
            'topup_petty_cash',
            "Top up kas kecil sebesar Rp " . number_format($validated['amount'], 0, ',', '.'),
            $pettyCash,
            ['balance' => $oldBalance],
            ['balance' => $pettyCash->current_balance]
        );

        return redirect()->route('petty-cash.index')
            ->with('success', 'Kas kecil berhasil di-top up.');
    }

    public function withdraw(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000',
            'notes' => 'required|string|max:500',
        ]);

        $pettyCash = PettyCash::first();

        if (!$pettyCash || $pettyCash->current_balance < $validated['amount']) {
            return back()->with('error', 'Saldo kas kecil tidak mencukupi.');
        }

        $oldBalance = $pettyCash->current_balance;
        $pettyCash->current_balance -= $validated['amount'];
        $pettyCash->save();

        // Create a transaction record
        $transaction = CashTransaction::create([
            'user_id' => auth()->id(),
            'type' => 'keluar',
            'amount' => $validated['amount'],
            'description' => 'Pengeluaran kas kecil: ' . $validated['notes'],
            'transaction_date' => now()->toDateString(),
            'status' => 'approved', // Auto approved for petty cash
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        ActivityLog::log(
            'withdraw_petty_cash',
            "Pengeluaran kas kecil sebesar Rp " . number_format($validated['amount'], 0, ',', '.') . ": " . $validated['notes'],
            $pettyCash,
            ['balance' => $oldBalance],
            ['balance' => $pettyCash->current_balance]
        );

        return redirect()->route('petty-cash.index')
            ->with('success', 'Pengeluaran kas kecil berhasil dicatat.');
    }
}
