<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name_en',
        'product_name_ar',
        'price',
        'quantity',
        'total',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getProductNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? ($this->product_name_ar ?: $this->product_name_en) : ($this->product_name_en ?: $this->product_name_ar);
    }
}
