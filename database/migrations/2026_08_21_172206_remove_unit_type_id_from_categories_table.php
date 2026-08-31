<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove o relacionamento antigo entre categorias e tipos de unidade.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['unit_type_id']);
            $table->dropColumn('unit_type_id');
        });
    }

    /**
     * Restaura o relacionamento antigo.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('unit_type_id')
                ->nullable()
                ->after('active')
                ->constrained('unit_types')
                ->nullOnDelete();
        });
    }
};
