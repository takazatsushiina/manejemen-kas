<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role helper methods
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function canApproveTransaction(): bool
    {
        return $this->role === 'admin';
    }

    public function canManageUsers(): bool
    {
        return $this->role === 'admin';
    }

    // Relationships
    public function transactions(): HasMany
    {
        return $this->hasMany(CashTransaction::class);
    }

    public function approvedTransactions(): HasMany
    {
        return $this->hasMany(CashTransaction::class, 'approved_by');
    }

    public function reconciliations(): HasMany
    {
        return $this->hasMany(Reconciliation::class);
    }

    public function approvedReconciliations(): HasMany
    {
        return $this->hasMany(Reconciliation::class, 'approved_by');
    }

    public function cashReports(): HasMany
    {
        return $this->hasMany(CashReport::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}
