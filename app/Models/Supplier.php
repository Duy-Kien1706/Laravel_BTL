<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'address',
        'description',
    ];

    /**
     * Danh sách các loại thuốc do nhà cung cấp này phân phối
     */
    public function medicines(): HasMany
    {
        return $this->hasMany(Medicine::class);
    }
}
