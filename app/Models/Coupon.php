<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value',
        'minimum_order_amount', 'maximum_discount',
        'usage_limit', 'used_count',
        'is_active', 'starts_at', 'expires_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit');
            });
    }

    public function calculateDiscount(float $orderTotal): float
    {
        if ($this->minimum_order_amount && $orderTotal < $this->minimum_order_amount) {
            return 0;
        }

        $discount = $this->type === 'percentage'
            ? $orderTotal * ($this->value / 100)
            : (float) $this->value;

        if ($this->maximum_discount) {
            $discount = min($discount, (float) $this->maximum_discount);
        }

        return round($discount, 2);
    }
}
