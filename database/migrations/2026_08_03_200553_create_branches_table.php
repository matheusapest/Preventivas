<?php

use App\Enums\BranchType;
use App\Enums\State;
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
        Schema::create('branches', function (Blueprint $table) {

            $table->id();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnUpdate()
                ->restrictOnDelete()
                ->comment('Empresa proprietária da filial');

            $table->foreignId('branch_code_id')
                ->constrained('branch_codes')
                ->cascadeOnUpdate()
                ->restrictOnDelete()
                ->comment('Código corporativo da filial');

            $table->string('name', 150)
                ->comment('Nome da filial');

            $table->string('city', 100)
                ->comment('Cidade da filial');

            $table->enum(
                'state',
                array_column(State::cases(), 'value')
            )->comment('Estado da filial');

            $table->enum(
                'type',
                array_column(BranchType::cases(), 'value')
            )->comment('Tipo da unidade');

            $table->boolean('active')
                ->default(true)
                ->comment('Indica se a filial está ativa');

            $table->timestamps();

            $table->unique(
                ['company_id', 'branch_code_id'],
                'branches_company_code_unique'
            );

        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
