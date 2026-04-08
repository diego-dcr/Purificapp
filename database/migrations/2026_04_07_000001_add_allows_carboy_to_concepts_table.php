<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('concepts', function (Blueprint $table) {
            $table->boolean('allows_carboy')->default(false)->after('type');
            $table->index('allows_carboy');
        });

        DB::table('concepts')
            ->where('type', 'income')
            ->update(['allows_carboy' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('concepts', function (Blueprint $table) {
            $table->dropIndex(['allows_carboy']);
            $table->dropColumn('allows_carboy');
        });
    }
};