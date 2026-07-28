<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('approved');
        });

        // Backfill: give every currently-approved, package-less member a fresh
        // 7-day trial starting now. Scoped to package-less members so a package
        // holder whose package later lapses doesn't get an unintended bonus trial.
        DB::table('users')
            ->join('members', 'members.user_id', '=', 'users.id')
            ->where('users.approved', 1)
            ->whereNull('users.approved_at')
            ->whereNull('members.current_package_id')
            ->update(['users.approved_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('approved_at');
        });
    }
};
