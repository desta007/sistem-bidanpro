<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'user_id',
        'exam_date',
        'type',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'weight',
        'height',
        'temperature',
        'pulse',
        'complaint',
        'diagnosis',
        'icd_code',
        'treatment',
        'notes',
        'hpht',
        'hpl',
        'pregnancy_week',
        'fetal_heart_rate',
        'fundal_height',
        'fetal_position',
        'kb_method',
        'kb_next_visit',
        'vaccine_type',
        'vaccine_batch',
        'next_vaccine_date',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'hpht' => 'date',
        'hpl' => 'date',
        'kb_next_visit' => 'date',
        'next_vaccine_date' => 'date',
        'blood_pressure_systolic' => 'decimal:2',
        'blood_pressure_diastolic' => 'decimal:2',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'temperature' => 'decimal:2',
        'fundal_height' => 'decimal:2',
    ];

    /**
     * Get the patient for this record
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the examiner (bidan/staff)
     */
    public function examiner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get blood pressure as formatted string
     */
    public function getBloodPressureAttribute(): ?string
    {
        if ($this->blood_pressure_systolic && $this->blood_pressure_diastolic) {
            return "{$this->blood_pressure_systolic}/{$this->blood_pressure_diastolic}";
        }
        return null;
    }

    /**
     * Get related invoice
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
