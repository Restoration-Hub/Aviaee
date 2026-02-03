<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill existing missions with no email to a default test email
        DB::table('missions')
            ->whereNull('email')
            ->update(['email' => 'test@example.com']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert backfilled emails set to the default test email
        DB::table('missions')
            ->where('email', 'test@example.com')
            ->update(['email' => null]);
    }
};
