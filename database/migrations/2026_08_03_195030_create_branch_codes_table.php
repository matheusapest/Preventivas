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
        Schema::create('branch_codes', function (Blueprint $table) {

            $table->id();

            $table->string('code', 20)
                ->unique()
                ->comment('Código corporativo da filial');

            $table->string('description', 150)
                ->nullable()
                ->comment('Descrição opcional do código');

            $table->boolean('active')
                ->default(true)
                ->comment('Indica se o código está ativo');

            $table->timestamps();

        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_codes');
    }
};
