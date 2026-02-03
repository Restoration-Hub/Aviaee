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
        // Backfill existing missions with status of delivered
        DB::table('missions')
            ->whereNotNull('status')
            ->update(['status' => 'delivered']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert backfilled status set to the default test status
        DB::table('missions')
            ->where('status', 'delivered')
            ->update(['status' => 'created']);
    }
};
