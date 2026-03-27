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
        Schema::create('waterjug_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('input_id')->constrained('inputs')->onDelete('cascade');
            $table->string('waterjug_barcode');
            $table->timestamp('timestamp')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waterjug_sales');
    }
};
