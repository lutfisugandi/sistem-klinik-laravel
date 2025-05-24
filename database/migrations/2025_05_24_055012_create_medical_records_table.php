<?php
// database/migrations/2024_01_01_000004_create_medical_records_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('visit_number')->unique(); // Auto-generated
            $table->enum('status', ['registered', 'vitals_checked', 'diagnosed', 'prescribed', 'completed'])
                  ->default('registered')->charset('utf8mb4');
            
            // Vitals (filled by perawat)
            $table->decimal('weight', 5, 2)->nullable(); // kg
            $table->string('blood_pressure')->nullable(); // 120/80
            $table->decimal('temperature', 4, 2)->nullable(); // celsius
            $table->integer('heart_rate')->nullable(); // bpm
            
            // Diagnosis (filled by dokter)
            $table->text('complaints')->nullable(); // keluhan
            $table->text('diagnosis')->nullable(); // hasil diagnosa
            $table->text('notes')->nullable(); // catatan dokter
            
            // Timestamps for each step
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('vitals_at')->nullable();
            $table->timestamp('diagnosed_at')->nullable();
            $table->timestamp('prescribed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('patient_id');
            $table->index('status');
            $table->index('visit_number');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
};