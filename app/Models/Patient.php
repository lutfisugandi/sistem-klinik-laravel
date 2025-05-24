<?php
// app/Models/Patient.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Patient extends Model
{
    protected $fillable = [
        'patient_number', 'name', 'birth_date', 'gender', 'phone', 'address'
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($patient) {
            // Generate patient number: P20241201001
            $today = now()->format('Ymd');
            $count = Patient::whereDate('created_at', today())->count() + 1;
            $patient->patient_number = 'P' . $today . str_pad($count, 3, '0', STR_PAD_LEFT);
        });
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function latestRecord()
    {
        return $this->hasOne(MedicalRecord::class)->latest();
    }

    public function getAgeAttribute()
    {
        return $this->birth_date->age;
    }

    public function getGenderDisplayAttribute()
    {
        return $this->gender === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}