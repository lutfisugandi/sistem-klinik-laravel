<?php
// database/migrations/2024_01_01_000001_add_role_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['pendaftaran', 'dokter', 'perawat', 'apoteker'])->default('pendaftaran')->charset('utf8mb4');
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Index for performance
            $table->index('role');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'is_active']);
        });
    }
};