<?php

namespace App\Http\Controllers;

use App\Models\CashTransaction;
use App\Models\PettyCash;
use App\Models\Reconciliation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Common stats for all roles
        $totalMasuk = CashTransaction::approved()->masuk()->sum('amount');
        $totalKeluar = CashTransaction::approved()->keluar()->sum('amount');
        $saldoKas = $totalMasuk - $totalKeluar;
        $pettyCashBalance = PettyCash::getBalance();

        // Pending transactions count
        $pendingTransactions = CashTransaction::pending()->count();
        $pendingReconciliations = Reconciliation::pending()->count();

        // Recent transactions
        $recentTransactions = CashTransaction::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly data for chart
        $monthlyData = CashTransaction::approved()
            ->select(
                DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN type = "masuk" THEN amount ELSE 0 END) as masuk'),
                DB::raw('SUM(CASE WHEN type = "keluar" THEN amount ELSE 0 END) as keluar')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();

        // Role-specific data
        $data = compact(
            'totalMasuk',
            'totalKeluar',
            'saldoKas',
            'pettyCashBalance',
            'pendingTransactions',
            'pendingReconciliations',
            'recentTransactions',
            'monthlyData'
        );

        // Add role-specific data
        if ($user->isAdmin()) {
            $data['userCount'] = User::count();
            $data['todayTransactions'] = CashTransaction::whereDate('transaction_date', today())->count();
        }

        return view('dashboard.index', $data);
    }
}
