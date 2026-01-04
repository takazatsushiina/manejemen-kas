<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCash extends Model
{
    use HasFactory;

    protected $table = 'petty_cash';

    protected $fillable = [
        'current_balance',
        'initial_balance',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'current_balance' => 'decimal:2',
            'initial_balance' => 'decimal:2',
        ];
    }

    public function getFormattedBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->current_balance, 0, ',', '.');
    }

    public static function getBalance(): float
    {
        $pettyCash = self::first();
        return $pettyCash ? $pettyCash->current_balance : 0;
    }

    public static function updateBalance(float $amount, string $type): void
    {
        $pettyCash = self::firstOrCreate([], [
            'current_balance' => 0,
            'initial_balance' => 0,
        ]);

        if ($type === 'masuk') {
            $pettyCash->current_balance += $amount;
        } else {
            $pettyCash->current_balance -= $amount;
        }

        $pettyCash->save();
    }
}
