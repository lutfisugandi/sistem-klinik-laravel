<?php
// app/Models/MedicalRecord.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicalRecord extends Model
{
    protected $fillable = [
        'patient_id', 'visit_number', 'status',
        'weight', 'blood_pressure', 'temperature', 'heart_rate',
        'complaints', 'diagnosis', 'notes',
        'registered_at', 'vitals_at', 'diagnosed_at', 'prescribed_at', 'completed_at'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'temperature' => 'decimal:2',
        'registered_at' => 'datetime',
        'vitals_at' => 'datetime',
        'diagnosed_at' => 'datetime',
        'prescribed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($record) {
            // Generate visit number: V20241201001
            $today = now()->format('Ymd');
            $count = MedicalRecord::whereDate('created_at', today())->count() + 1;
            $record->visit_number = 'V' . $today . str_pad($count, 4, '0', STR_PAD_LEFT);
            $record->registered_at = now();
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'registered' => 'Terdaftar',
            'vitals_checked' => 'Cek Vital',
            'diagnosed' => 'Sudah Didiagnosa',
            'prescribed' => 'Sudah Diresepkan',
            'completed' => 'Selesai'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'registered' => 'primary',
            'vitals_checked' => 'info',
            'diagnosed' => 'warning',
            'prescribed' => 'secondary',
            'completed' => 'success'
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    public function canUpdateVitals()
    {
        return in_array($this->status, ['registered', 'vitals_checked']);
    }

    public function canDiagnose()
    {
        return in_array($this->status, ['vitals_checked', 'diagnosed']);
    }

    public function canPrescribe()
    {
        return in_array($this->status, ['diagnosed', 'prescribed']);
    }

    public function getBmiAttribute()
    {
        if (!$this->weight) return null;
        
        // Estimasi tinggi berdasarkan gender dan umur (simplified)
        $height = $this->patient->gender === 'L' ? 1.70 : 1.60; // meter
        return round($this->weight / ($height * $height), 1);
    }
}