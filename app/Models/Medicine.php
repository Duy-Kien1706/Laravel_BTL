<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'code',
        'category',
        'unit',
        'price',
        'stock',
        'expiry_date',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'date',
            'price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    /**
     * Kiểm tra thuốc có sắp hết hạn không (trong vòng 30 ngày hoặc đã hết hạn)
     */
    public function isExpiringSoon(): bool
    {
        return $this->expiry_date->isPast() || $this->expiry_date->diffInDays(now()) <= 30;
    }

    /**
     * Kiểm tra thuốc đã hết hạn chưa
     */
    public function isExpired(): bool
    {
        return $this->expiry_date->isPast();
    }

    /**
     * Kiểm tra thuốc sắp hết hàng (tồn kho <= 10)
     */
    public function isLowStock(): bool
    {
        return $this->stock <= 10;
    }

    /**
     * Định dạng giá tiền VNĐ
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.') . ' VNĐ';
    }
}
