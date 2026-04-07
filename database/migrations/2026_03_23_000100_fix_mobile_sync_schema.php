<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sales')) {
            Schema::table('sales', function (Blueprint $table): void {
                if (! Schema::hasColumn('sales', 'external_id')) {
                    $table->string('external_id')->nullable()->unique()->after('created_by');
                }

                if (! Schema::hasColumn('sales', 'latitude')) {
                    $table->string('latitude')->nullable()->after('external_id');
                }

                if (! Schema::hasColumn('sales', 'longitude')) {
                    $table->string('longitude')->nullable()->after('latitude');
                }
            });
        }

        if (Schema::hasTable('retornos')) {
            Schema::table('retornos', function (Blueprint $table): void {
                if (! Schema::hasColumn('retornos', 'external_id')) {
                    $table->string('external_id')->nullable()->unique()->after('created_by');
                }

                if (! Schema::hasColumn('retornos', 'latitude')) {
                    $table->string('latitude')->nullable()->after('external_id');
                }

                if (! Schema::hasColumn('retornos', 'longitude')) {
                    $table->string('longitude')->nullable()->after('latitude');
                }
            });
        }

        if (Schema::hasTable('carboy_returns') && ! Schema::hasTable('carboy_retornos')) {
            Schema::rename('carboy_returns', 'carboy_retornos');
        }

        if (Schema::hasTable('carboy_retornos')) {
            Schema::table('carboy_retornos', function (Blueprint $table): void {
                if (Schema::hasColumn('carboy_retornos', 'return_id') && ! Schema::hasColumn('carboy_retornos', 'retorno_id')) {
                    $table->renameColumn('return_id', 'retorno_id');
                }

                if (Schema::hasColumn('carboy_retornos', 'carboy_barcode') && ! Schema::hasColumn('carboy_retornos', 'carboy_codebar')) {
                    $table->renameColumn('carboy_barcode', 'carboy_codebar');
                }
            });
        }

        if (Schema::hasTable('carboy_sales')) {
            Schema::table('carboy_sales', function (Blueprint $table): void {
                if (Schema::hasColumn('carboy_sales', 'carboy_barcode') && ! Schema::hasColumn('carboy_sales', 'carboy_codebar')) {
                    $table->renameColumn('carboy_barcode', 'carboy_codebar');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('carboy_sales')) {
            Schema::table('carboy_sales', function (Blueprint $table): void {
                if (Schema::hasColumn('carboy_sales', 'carboy_codebar') && ! Schema::hasColumn('carboy_sales', 'carboy_barcode')) {
                    $table->renameColumn('carboy_codebar', 'carboy_barcode');
                }
            });
        }

        if (Schema::hasTable('carboy_retornos')) {
            Schema::table('carboy_retornos', function (Blueprint $table): void {
                if (Schema::hasColumn('carboy_retornos', 'carboy_codebar') && ! Schema::hasColumn('carboy_retornos', 'carboy_barcode')) {
                    $table->renameColumn('carboy_codebar', 'carboy_barcode');
                }

                if (Schema::hasColumn('carboy_retornos', 'retorno_id') && ! Schema::hasColumn('carboy_retornos', 'return_id')) {
                    $table->renameColumn('retorno_id', 'return_id');
                }
            });
        }

        if (Schema::hasTable('carboy_retornos') && ! Schema::hasTable('carboy_returns')) {
            Schema::rename('carboy_retornos', 'carboy_returns');
        }

        if (Schema::hasTable('sales')) {
            Schema::table('sales', function (Blueprint $table): void {
                foreach (['longitude', 'latitude', 'external_id'] as $column) {
                    if (Schema::hasColumn('sales', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('retornos')) {
            Schema::table('retornos', function (Blueprint $table): void {
                foreach (['longitude', 'latitude', 'external_id'] as $column) {
                    if (Schema::hasColumn('retornos', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};