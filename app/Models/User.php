<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'status',
        'salary',
        'password',
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
            'salary' => 'decimal:2',
        ];
    }

    /**
     * Danh sách hóa đơn do nhân viên này lập
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Kiểm tra có phải quản trị viên không
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Tên chức vụ bằng tiếng Việt
     */
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Quản trị viên (Admin)',
            'pharmacist' => 'Dược sĩ bán hàng',
            default => 'Dược sĩ bán hàng',
        };
    }

    /**
     * Định dạng lương cơ bản
     */
    public function getFormattedSalaryAttribute(): string
    {
        return number_format($this->salary, 0, ',', '.') . ' VNĐ';
    }
}
