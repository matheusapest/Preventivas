<?php

use App\Enums\CompanyType;
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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('nome', 150)
                ->unique()
                ->comment('Nome da empresa');

            $table->enum(
                'tipo',
                array_column(CompanyType::cases(), 'value')
            )->comment('Grupo Empresarial ou Empresa Terceirizada');

            $table->boolean('ativo')
                ->default(true)
                ->comment('Indica se a empresa está ativa');

            $table->timestamps();
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
