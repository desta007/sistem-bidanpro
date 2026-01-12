<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'queue_date',
        'queue_number',
        'service_type',
        'status',
        'called_at',
        'finished_at',
        'notes',
    ];

    protected $casts = [
        'queue_date' => 'date',
        'called_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * Generate next queue number for today
     */
    public static function getNextNumber(?string $date = null): int
    {
        $date = $date ?? date('Y-m-d');
        $lastQueue = static::where('queue_date', $date)
            ->orderBy('queue_number', 'desc')
            ->first();

        return $lastQueue ? $lastQueue->queue_number + 1 : 1;
    }

    /**
     * Get the patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Scope for today's queue
     */
    public function scopeToday($query)
    {
        return $query->where('queue_date', date('Y-m-d'));
    }

    /**
     * Scope for waiting patients
     */
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }
}
