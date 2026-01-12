<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Patient extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'gender',
        'birth_date',
        'birth_place',
        'address',
        'phone',
        'password',
        'blood_type',
        'allergy',
        'bpjs_number',
        'husband_name',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Get patient's full name with optional NIK
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->nik})";
    }

    /**
     * Get patient's age
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }
        return $this->birth_date->age;
    }

    /**
     * Get all medical records for this patient
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    /**
     * Get all invoices for this patient
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get all queue entries for this patient
     */
    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    /**
     * Get the latest ANC record
     */
    public function latestANCRecord()
    {
        return $this->hasOne(MedicalRecord::class)
            ->where('type', 'ANC')
            ->latest('exam_date');
    }
}
