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
        Schema::table('activity_categories', function (Blueprint $table) {
            $table->string('name', 100)
                ->after('id');

            $table->boolean('active')
                ->default(true)
                ->after('name');
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::table('activity_categories', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'active',
            ]);
        });
    }
};
