<?php
// database/migrations/2024_01_01_000005_create_prescriptions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->string('dosage'); // 3x1, 2x1, dll
            $table->text('instructions'); // sesudah makan, dll
            $table->integer('quantity'); // jumlah yang diberikan
            $table->timestamps();
            
            // Indexes for performance
            $table->index('medical_record_id');
            $table->index('medicine_id');
            
            // Prevent duplicate medicine in same prescription
            $table->unique(['medical_record_id', 'medicine_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('prescriptions');
    }
};