<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('carboy_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('output_id')->constrained('outputs')->onDelete('cascade');
            $table->string('carboy_barcode');
            $table->timestamp('timestamp')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carboy_outputs');
    }
};
