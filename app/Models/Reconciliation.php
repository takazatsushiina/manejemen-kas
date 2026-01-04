<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reconciliation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_balance',
        'cash_book_balance',
        'difference',
        'reconciliation_date',
        'status',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'bank_balance' => 'decimal:2',
            'cash_book_balance' => 'decimal:2',
            'difference' => 'decimal:2',
            'reconciliation_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function getFormattedBankBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->bank_balance, 0, ',', '.');
    }

    public function getFormattedCashBookBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->cash_book_balance, 0, ',', '.');
    }

    public function getFormattedDifferenceAttribute(): string
    {
        $prefix = $this->difference >= 0 ? '+' : '';
        return $prefix . 'Rp ' . number_format($this->difference, 0, ',', '.');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'pending' => '<span class="badge bg-warning">Menunggu</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }
}
