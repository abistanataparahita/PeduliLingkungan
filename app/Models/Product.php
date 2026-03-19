<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'purchase_price',
        'selling_price',
        'discount_price',
        'image',
        'current_stock',
        'min_stock',
        'max_stock',
        'pcs',
        'is_preorder',
        'preorder_estimate',
        'preorder_open_until',
        'preorder_quota',
        'preorder_filled',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'current_stock' => 'integer',
            'min_stock' => 'integer',
            'max_stock' => 'integer',
            'is_preorder' => 'boolean',
            'preorder_open_until' => 'date',
            'preorder_quota' => 'integer',
            'preorder_filled' => 'integer',
        ];
    }

    public function preorders(): HasMany
    {
        return $this->hasMany(Preorder::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->current_stock <= $this->min_stock) {
            return 'low';
        }
        if ($this->max_stock && $this->current_stock >= $this->max_stock) {
            return 'full';
        }
        return 'ok';
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_price !== null
            && (float) $this->discount_price > 0
            && (float) $this->discount_price < (float) $this->selling_price;
    }

    public function getFinalPriceAttribute(): float
    {
        if ($this->has_discount) {
            return (float) $this->discount_price;
        }
        return (float) $this->selling_price;
    }

    public function getFormattedSellingPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->selling_price, 0, ',', '.');
    }

    public function getFormattedDiscountPriceAttribute(): ?string
    {
        return $this->discount_price !== null
            ? 'Rp ' . number_format((float) $this->discount_price, 0, ',', '.')
            : null;
    }

    public function getFormattedFinalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->final_price, 0, ',', '.');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /** Pre order dianggap closed jika tanggal lewat atau kuota terpenuhi */
    public function getIsPreorderClosedAttribute(): bool
    {
        if (! $this->is_preorder) {
            return true;
        }
        if ($this->preorder_open_until !== null && now()->isAfter(\Carbon\Carbon::parse($this->preorder_open_until)->endOfDay())) {
            return true;
        }
        if ($this->preorder_quota !== null && $this->preorder_filled >= $this->preorder_quota) {
            return true;
        }
        return false;
    }

    public function getPreorderRemainingQuotaAttribute(): ?int
    {
        if ($this->preorder_quota === null) {
            return null;
        }
        return max(0, $this->preorder_quota - $this->preorder_filled);
    }
}
