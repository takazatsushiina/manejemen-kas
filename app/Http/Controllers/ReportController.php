<?php

namespace App\Http\Controllers;

use App\Models\CashTransaction;
use App\Models\CashReport;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        // Get transactions for the period
        $transactions = CashTransaction::approved()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date')
            ->get();

        $totalMasuk = $transactions->where('type', 'masuk')->sum('amount');
        $totalKeluar = $transactions->where('type', 'keluar')->sum('amount');
        $netCashFlow = $totalMasuk - $totalKeluar;

        // Group by date for daily summary
        $dailySummary = $transactions->groupBy(function ($item) {
            return $item->transaction_date->format('Y-m-d');
        })->map(function ($group) {
            return [
                'masuk' => $group->where('type', 'masuk')->sum('amount'),
                'keluar' => $group->where('type', 'keluar')->sum('amount'),
                'count' => $group->count(),
            ];
        });

        // Previous reports
        $previousReports = CashReport::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('reports.index', compact(
            'transactions',
            'totalMasuk',
            'totalKeluar',
            'netCashFlow',
            'dailySummary',
            'previousReports',
            'startDate',
            'endDate'
        ));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:harian,mingguan,bulanan',
            'format' => 'required|in:pdf,excel',
        ]);

        // Get transactions for the period
        $transactions = CashTransaction::approved()
            ->whereBetween('transaction_date', [$validated['start_date'], $validated['end_date']])
            ->with('user')
            ->orderBy('transaction_date')
            ->get();

        $totalMasuk = $transactions->where('type', 'masuk')->sum('amount');
        $totalKeluar = $transactions->where('type', 'keluar')->sum('amount');

        // Save report record
        $report = CashReport::create([
            'user_id' => auth()->id(),
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'file_format' => $validated['format'],
        ]);

        ActivityLog::log('export_report', 'Exported ' . $validated['type'] . ' report', $report);

        // Handle Excel export
        if ($validated['format'] === 'excel') {
            return $this->exportToExcel($transactions, $totalMasuk, $totalKeluar, $validated);
        }

        // Return HTML/printable view for PDF
        return view('reports.export', compact('transactions', 'totalMasuk', 'totalKeluar', 'validated'));
    }

    /**
     * Export transactions to Excel format
     */
    private function exportToExcel($transactions, $totalMasuk, $totalKeluar, $validated)
    {
        $filename = 'laporan_kas_' . $validated['start_date'] . '_' . $validated['end_date'] . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function () use ($transactions, $totalMasuk, $totalKeluar, $validated) {
            $output = fopen('php://output', 'w');

            // Add BOM for proper encoding in Excel
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Title
            fputcsv($output, ['LAPORAN ARUS KAS'], "\t");
            fputcsv($output, ['Periode: ' . $validated['start_date'] . ' s/d ' . $validated['end_date']], "\t");
            fputcsv($output, ['Tipe: ' . ucfirst($validated['type'])], "\t");
            fputcsv($output, ['Dicetak: ' . now()->format('d/m/Y H:i')], "\t");
            fputcsv($output, [], "\t");

            // Summary
            fputcsv($output, ['RINGKASAN'], "\t");
            fputcsv($output, ['Total Kas Masuk', 'Rp ' . number_format($totalMasuk, 0, ',', '.')], "\t");
            fputcsv($output, ['Total Kas Keluar', 'Rp ' . number_format($totalKeluar, 0, ',', '.')], "\t");
            fputcsv($output, ['Arus Kas Bersih', 'Rp ' . number_format($totalMasuk - $totalKeluar, 0, ',', '.')], "\t");
            fputcsv($output, [], "\t");

            // Headers
            fputcsv($output, ['No', 'Tanggal', 'Deskripsi', 'Tipe', 'Nominal', 'Oleh'], "\t");

            // Data
            foreach ($transactions as $index => $tx) {
                fputcsv($output, [
                    $index + 1,
                    $tx->transaction_date->format('d/m/Y'),
                    $tx->description ?: '-',
                    $tx->isMasuk() ? 'Masuk' : 'Keluar',
                    ($tx->isMasuk() ? '+' : '-') . 'Rp ' . number_format($tx->amount, 0, ',', '.'),
                    $tx->user->name,
                ], "\t");
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function daily(Request $request)
    {
        $date = $request->date ?? now()->toDateString();

        $transactions = CashTransaction::approved()
            ->whereDate('transaction_date', $date)
            ->with('user')
            ->orderBy('created_at')
            ->get();

        $summary = [
            'masuk' => $transactions->where('type', 'masuk')->sum('amount'),
            'keluar' => $transactions->where('type', 'keluar')->sum('amount'),
        ];

        return view('reports.daily', compact('transactions', 'summary', 'date'));
    }
}
