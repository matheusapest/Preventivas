<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa a migration.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('unit_type_id')
                ->nullable()
                ->after('active')
                ->constrained('unit_types')
                ->nullOnDelete();
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['unit_type_id']);
            $table->dropColumn('unit_type_id');
        });
    }
};
