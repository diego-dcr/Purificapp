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
        Schema::create('carboys', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique();
            $table->string('conservation_state');
            $table->foreignId('lot_id')->constrained('lots')->onDelete('cascade');
            $table->enum('status', [
                'En_planta',
                'En_ruta',
                'Con_cliente',
                'Retornado',
                'Perdido',
                'Mantenimiento',
                'Retirado',
            ]);
            $table->timestamp('timestamp')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carboys');
    }
};
