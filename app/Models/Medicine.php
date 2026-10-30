<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'code',
        'category_id',
        'supplier_id',
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
     * Danh mục của loại thuốc này
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Chi tiết các hóa đơn chứa loại thuốc này
     */
    public function invoiceDetails(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    /**
     * Nhà cung cấp của loại thuốc này
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Kiểm tra thuốc đã hết hạn chưa (hạn sử dụng đã qua)
     */
    public function isExpired(): bool
    {
        return $this->expiry_date->endOfDay()->isPast();
    }

    /**
     * Kiểm tra thuốc có sắp hết hạn không (còn hạn nhưng sẽ hết hạn trong vòng 30 ngày tới)
     */
    public function isExpiringSoon(): bool
    {
        return !$this->isExpired() && $this->expiry_date->lte(now()->addDays(30)->endOfDay());
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
