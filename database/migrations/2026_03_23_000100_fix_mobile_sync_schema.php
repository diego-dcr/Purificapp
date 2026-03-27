<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('inputs')) {
            Schema::table('inputs', function (Blueprint $table): void {
                if (! Schema::hasColumn('inputs', 'external_id')) {
                    $table->string('external_id')->nullable()->unique()->after('created_by');
                }

                if (! Schema::hasColumn('inputs', 'latitude')) {
                    $table->string('latitude')->nullable()->after('external_id');
                }

                if (! Schema::hasColumn('inputs', 'longitude')) {
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

        if (Schema::hasTable('waterjug_returns') && ! Schema::hasTable('waterjug_outputs')) {
            Schema::rename('waterjug_returns', 'waterjug_outputs');
        }

        if (Schema::hasTable('waterjug_outputs')) {
            Schema::table('waterjug_outputs', function (Blueprint $table): void {
                if (Schema::hasColumn('waterjug_outputs', 'return_id') && ! Schema::hasColumn('waterjug_outputs', 'output_id')) {
                    $table->renameColumn('return_id', 'output_id');
                }

                if (Schema::hasColumn('waterjug_outputs', 'waterjug_barcode') && ! Schema::hasColumn('waterjug_outputs', 'waterjug_codebar')) {
                    $table->renameColumn('waterjug_barcode', 'waterjug_codebar');
                }
            });
        }

        if (Schema::hasTable('waterjug_sales')) {
            Schema::table('waterjug_sales', function (Blueprint $table): void {
                if (Schema::hasColumn('waterjug_sales', 'waterjug_barcode') && ! Schema::hasColumn('waterjug_sales', 'waterjug_codebar')) {
                    $table->renameColumn('waterjug_barcode', 'waterjug_codebar');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('waterjug_sales')) {
            Schema::table('waterjug_sales', function (Blueprint $table): void {
                if (Schema::hasColumn('waterjug_sales', 'waterjug_codebar') && ! Schema::hasColumn('waterjug_sales', 'waterjug_barcode')) {
                    $table->renameColumn('waterjug_codebar', 'waterjug_barcode');
                }
            });
        }

        if (Schema::hasTable('waterjug_outputs')) {
            Schema::table('waterjug_outputs', function (Blueprint $table): void {
                if (Schema::hasColumn('waterjug_outputs', 'waterjug_codebar') && ! Schema::hasColumn('waterjug_outputs', 'waterjug_barcode')) {
                    $table->renameColumn('waterjug_codebar', 'waterjug_barcode');
                }

                if (Schema::hasColumn('waterjug_outputs', 'output_id') && ! Schema::hasColumn('waterjug_outputs', 'return_id')) {
                    $table->renameColumn('output_id', 'return_id');
                }
            });
        }

        if (Schema::hasTable('waterjug_outputs') && ! Schema::hasTable('waterjug_returns')) {
            Schema::rename('waterjug_outputs', 'waterjug_returns');
        }

        if (Schema::hasTable('inputs')) {
            Schema::table('inputs', function (Blueprint $table): void {
                foreach (['longitude', 'latitude', 'external_id'] as $column) {
                    if (Schema::hasColumn('inputs', $column)) {
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