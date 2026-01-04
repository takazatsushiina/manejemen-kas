<?php

namespace App\Http\Controllers;

use App\Models\Reconciliation;
use App\Models\CashTransaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ReconciliationController extends Controller
{
    public function index()
    {
        $reconciliations = Reconciliation::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('reconciliations.index', compact('reconciliations'));
    }

    public function create()
    {
        // Calculate current cash book balance from approved transactions
        $totalMasuk = CashTransaction::approved()->masuk()->sum('amount');
        $totalKeluar = CashTransaction::approved()->keluar()->sum('amount');
        $cashBookBalance = $totalMasuk - $totalKeluar;

        return view('reconciliations.create', compact('cashBookBalance'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_balance' => 'required|numeric',
            'reconciliation_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate cash book balance
        $totalMasuk = CashTransaction::approved()->masuk()->sum('amount');
        $totalKeluar = CashTransaction::approved()->keluar()->sum('amount');
        $cashBookBalance = $totalMasuk - $totalKeluar;

        $difference = $validated['bank_balance'] - $cashBookBalance;

        $reconciliation = Reconciliation::create([
            'user_id' => auth()->id(),
            'bank_balance' => $validated['bank_balance'],
            'cash_book_balance' => $cashBookBalance,
            'difference' => $difference,
            'reconciliation_date' => $validated['reconciliation_date'],
            'status' => 'approved',
            'notes' => $validated['notes'],
        ]);

        ActivityLog::log('create_reconciliation', 'Created reconciliation with difference: Rp ' . number_format($difference, 0, ',', '.'), $reconciliation);

        return redirect()->route('reconciliations.index')
            ->with('success', 'Rekonsiliasi berhasil dibuat.');
    }

    public function show(Reconciliation $reconciliation)
    {
        $reconciliation->load('user');
        return view('reconciliations.show', compact('reconciliation'));
    }
}
