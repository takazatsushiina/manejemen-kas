<?php

namespace App\Providers;

use App\Models\CashTransaction;
use App\Models\Reconciliation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share notification data with all views using layouts.app
        View::composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $pendingTransactions = CashTransaction::pending()->count();
                $pendingReconciliations = 0;

                // Only count reconciliations if user can approve
                if (auth()->user()->canApproveTransaction()) {
                    $pendingReconciliations = Reconciliation::pending()->count();
                }

                $notificationCount = $pendingTransactions + $pendingReconciliations;

                $view->with([
                    'notificationCount' => $notificationCount,
                    'pendingTransactionsCount' => $pendingTransactions,
                    'pendingReconciliationsCount' => $pendingReconciliations,
                ]);
            } else {
                $view->with([
                    'notificationCount' => 0,
                    'pendingTransactionsCount' => 0,
                    'pendingReconciliationsCount' => 0,
                ]);
            }
        });
    }
}
