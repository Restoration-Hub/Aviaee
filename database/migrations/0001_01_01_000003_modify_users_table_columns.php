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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number')->unique();
            $table->string('user_type')->default('buyer'); // e.g., 'buyer', 'pilot', 'seller'

            $table->dropColumn('name');
            $table->dropColumn('email_verified_at');
            $table->dropColumn('remember_token');
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
