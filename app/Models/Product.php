<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'barcode',
        'short_description', 'description',
        'price', 'compare_price', 'cost_price',
        'stock_quantity', 'low_stock_threshold', 'weight',
        'thumbnail', 'video_url',
        'is_active', 'is_featured',
        'is_flash_sale', 'flash_sale_start', 'flash_sale_end', 'flash_sale_price',
        'meta_title', 'meta_description', 'meta_keywords',
        'view_count', 'sold_count',
    ];

    protected $appends = [
        'effective_price',
        'discount_percentage',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'flash_sale_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_flash_sale' => 'boolean',
        'flash_sale_start' => 'datetime',
        'flash_sale_end' => 'datetime',
    ];

    // --- Relationships ---

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeFlashSale($query)
    {
        return $query->where('is_flash_sale', true)
            ->where('flash_sale_start', '<=', now())
            ->where('flash_sale_end', '>=', now());
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold');
    }

    // --- Accessors ---

    public function getEffectivePriceAttribute(): float
    {
        if ($this->is_flash_sale && $this->flash_sale_price
            && now()->between($this->flash_sale_start, $this->flash_sale_end)) {
            return (float) $this->flash_sale_price;
        }
        return (float) $this->price;
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return null;
    }
}
