<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate:refresh (does a rollback first and then migrates)
     * php artisan migrate (runs the up() method to apply changes to the database)
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->unique();
        });
    }

    /**
     * Reverse the migrations.
     * This method is executed when you rollback a migration 
     * (e.g., php artisan migrate:rollback). 
     * It describes how to reverse the changes made by the up() method.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};