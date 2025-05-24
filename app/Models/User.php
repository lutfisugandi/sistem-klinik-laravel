<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    // Helper methods untuk role checking
    public function isDokter()
    {
        return $this->role === 'dokter';
    }

    public function isPerawat()
    {
        return $this->role === 'perawat';
    }

    public function isPendaftaran()
    {
        return $this->role === 'pendaftaran';
    }

    public function isApoteker()
    {
        return $this->role === 'apoteker';
    }

    public function getRoleDisplayAttribute()
    {
        $roles = [
            'pendaftaran' => 'Admin Pendaftaran',
            'dokter' => 'Dokter',
            'perawat' => 'Perawat',
            'apoteker' => 'Apoteker'
        ];
        
        return $roles[$this->role] ?? $this->role;
    }
}