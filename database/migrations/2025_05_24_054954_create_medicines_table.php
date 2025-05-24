<?php
// database/migrations/2024_01_01_000003_create_medicines_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // tablet, sirup, salep, dll
            $table->text('description')->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->string('unit')->default('pcs'); // pcs, ml, gram
            $table->timestamps();
            
            // Indexes for performance
            $table->index('name');
            $table->index('type');
            $table->index('stock');
        });
    }

    public function down()
    {
        Schema::dropIfExists('medicines');
    }
};