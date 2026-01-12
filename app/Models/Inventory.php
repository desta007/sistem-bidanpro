<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category',
        'unit',
        'stock',
        'min_stock',
        'buy_price',
        'sell_price',
        'batch_number',
        'expired_date',
        'is_active',
    ];

    protected $casts = [
        'expired_date' => 'date',
        'buy_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Check if stock is low
     */
    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    /**
     * Check if item is expired
     */
    public function isExpired(): bool
    {
        if (!$this->expired_date) {
            return false;
        }
        return $this->expired_date->isPast();
    }

    /**
     * Get transactions for this item
     */
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    /**
     * Get invoice items using this inventory
     */
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
