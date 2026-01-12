<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'service_id',
        'inventory_id',
        'item_type',
        'item_name',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the invoice
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the service (if item_type is service)
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the inventory item (if item_type is product)
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
