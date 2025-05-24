<?php
// app/Models/Medicine.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    protected $fillable = [
        'name', 'type', 'description', 'stock', 'price', 'unit'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer'
    ];

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function isLowStock()
    {
        return $this->stock <= 10;
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock <= 5) return 'critical';
        if ($this->stock <= 10) return 'low';
        if ($this->stock <= 50) return 'medium';
        return 'good';
    }
}