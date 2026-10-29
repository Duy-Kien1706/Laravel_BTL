<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'supplier',
        'user_id',
        'total_amount',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(WarehouseReceiptDetail::class);
    }

    public function getFormattedTotalAmountAttribute(): string
    {
        return number_format($this->total_amount, 0, ',', '.') . ' VNĐ';
    }

    public function getTypeNameAttribute(): string
    {
        return $this->type === 'import' ? 'Nhập kho' : 'Xuất kho';
    }
}
