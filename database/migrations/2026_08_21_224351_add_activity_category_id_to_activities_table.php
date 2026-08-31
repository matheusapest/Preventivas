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
        Schema::table('activities', function (Blueprint $table) {
            $table->foreignId('activity_category_id')
                ->nullable()
                ->after('preventive_type_id')
                ->constrained('activity_categories')
                ->restrictOnDelete();

            $table->index([
                'activity_category_id',
                'active',
            ]);
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign([
                'activity_category_id',
            ]);

            $table->dropIndex([
                'activity_category_id',
                'active',
            ]);

            $table->dropColumn('activity_category_id');
        });
    }
};
