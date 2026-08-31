<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Executa a migration.
     */
    public function up(): void
    {
        Schema::create('category_unit_type', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            $table->foreignId('unit_type_id')
                ->constrained('unit_types')
                ->cascadeOnDelete();

            $table->unique([
                'category_id',
                'unit_type_id',
            ]);
        });

        /*
         * Migra os relacionamentos existentes.
         *
         * Cada categoria que atualmente possui um
         * unit_type_id passa a ter esse relacionamento
         * registrado na tabela pivot.
         */
        DB::table('categories')
            ->whereNotNull('unit_type_id')
            ->select('id', 'unit_type_id')
            ->orderBy('id')
            ->each(function ($category) {
                DB::table('category_unit_type')->insert([
                    'category_id' => $category->id,
                    'unit_type_id' => $category->unit_type_id,
                ]);
            });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_unit_type');
    }
};
