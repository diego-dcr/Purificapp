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

        if (Schema::hasTable('outputs')) {
            Schema::table('outputs', function (Blueprint $table): void {
                if (! Schema::hasColumn('outputs', 'external_id')) {
                    $table->string('external_id')->nullable()->unique()->after('created_by');
                }

                if (! Schema::hasColumn('outputs', 'latitude')) {
                    $table->string('latitude')->nullable()->after('external_id');
                }

                if (! Schema::hasColumn('outputs', 'longitude')) {
                    $table->string('longitude')->nullable()->after('latitude');
                }
            });
        }

        if (Schema::hasTable('carboy_returns') && ! Schema::hasTable('carboy_outputs')) {
            Schema::rename('carboy_returns', 'carboy_outputs');
        }

        if (Schema::hasTable('carboy_outputs')) {
            Schema::table('carboy_outputs', function (Blueprint $table): void {
                if (Schema::hasColumn('carboy_outputs', 'return_id') && ! Schema::hasColumn('carboy_outputs', 'output_id')) {
                    $table->renameColumn('return_id', 'output_id');
                }

                if (Schema::hasColumn('carboy_outputs', 'carboy_barcode') && ! Schema::hasColumn('carboy_outputs', 'carboy_codebar')) {
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

        if (Schema::hasTable('carboy_outputs')) {
            Schema::table('carboy_outputs', function (Blueprint $table): void {
                if (Schema::hasColumn('carboy_outputs', 'carboy_codebar') && ! Schema::hasColumn('carboy_outputs', 'carboy_barcode')) {
                    $table->renameColumn('carboy_codebar', 'carboy_barcode');
                }

                if (Schema::hasColumn('carboy_outputs', 'output_id') && ! Schema::hasColumn('carboy_outputs', 'return_id')) {
                    $table->renameColumn('output_id', 'return_id');
                }
            });
        }

        if (Schema::hasTable('carboy_outputs') && ! Schema::hasTable('carboy_returns')) {
            Schema::rename('carboy_outputs', 'carboy_returns');
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

        if (Schema::hasTable('outputs')) {
            Schema::table('outputs', function (Blueprint $table): void {
                foreach (['longitude', 'latitude', 'external_id'] as $column) {
                    if (Schema::hasColumn('outputs', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};