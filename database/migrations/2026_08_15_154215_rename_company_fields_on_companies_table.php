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
        Schema::table('companies', function (Blueprint $table) {
            $table->renameColumn('nome', 'name');
            $table->renameColumn('tipo', 'type');
            $table->renameColumn('ativo', 'active');
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->renameColumn('name', 'nome');
            $table->renameColumn('type', 'tipo');
            $table->renameColumn('active', 'ativo');
        });
    }
};
