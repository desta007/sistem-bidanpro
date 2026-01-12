<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'patient_id',
        'medical_record_id',
        'user_id',
        'invoice_date',
        'subtotal',
        'discount',
        'total',
        'paid_amount',
        'payment_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    /**
     * Generate next invoice number
     */
    public static function generateNumber(): string
    {
        $prefix = 'INV-' . date('Ymd');
        $lastInvoice = static::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return $prefix . '-' . $nextNumber;
    }

    /**
     * Check if invoice is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Get outstanding amount
     */
    public function getOutstandingAttribute(): float
    {
        return max(0, $this->total - $this->paid_amount);
    }

    /**
     * Get the patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the medical record
     */
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    /**
     * Get the cashier
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get invoice items
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
