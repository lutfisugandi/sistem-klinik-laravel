<?php
// database/migrations/2024_01_01_000002_create_patients_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_number')->unique(); // Auto-generated
            $table->string('name');
            $table->date('birth_date');
            $table->enum('gender', ['L', 'P'])->charset('utf8mb4'); // Laki-laki, Perempuan
            $table->string('phone');
            $table->text('address')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('name');
            $table->index('patient_number');
            $table->index('phone');
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
};