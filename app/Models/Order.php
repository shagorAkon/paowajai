<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id',
        'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'shipping_city', 'shipping_district', 'shipping_division', 'shipping_zip',
        'subtotal', 'shipping_cost', 'discount', 'total',
        'coupon_code', 'status', 'payment_method', 'payment_status',
        'tracking_number', 'courier', 'notes', 'source',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // --- Relationships ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // --- Scopes ---

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // --- Helpers ---

    public static function generateOrderNumber(): string
    {
        return 'PAO-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }
}
