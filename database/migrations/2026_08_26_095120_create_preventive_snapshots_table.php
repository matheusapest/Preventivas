<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('preventive_snapshots', function (Blueprint $table) {

            $table->id();

            /*
             * Preventiva à qual este snapshot pertence.
             *
             * É uma referência à instância real da preventiva.
             */
            $table->foreignId('preventive_id')
                ->constrained('preventives')
                ->restrictOnDelete();

            /*
             * Tipo de preventiva utilizado na criação da instância.
             *
             * Mantemos a FK para rastreabilidade da origem.
             */
            $table->foreignId('preventive_type_id')
                ->constrained('preventive_types')
                ->restrictOnDelete();

            /*
             * Perfil de preventiva utilizado como template.
             */
            $table->foreignId('preventive_profile_id')
                ->constrained('preventive_profiles')
                ->restrictOnDelete();

            /*
             * Filial onde a preventiva será executada.
             */
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->restrictOnDelete();

            /*
             * --------------------------------------------------------------
             * DADOS CONGELADOS
             * --------------------------------------------------------------
             *
             * Estes campos NÃO são foreignId.
             *
             * O objetivo é preservar o nome que existia no momento
             * da criação da preventiva.
             *
             * Exemplo:
             *
             * Hoje:
             *   Tipo = Preventiva de PDV
             *
             * Amanhã o gestor pode alterar o nome para:
             *   Preventiva de PDV - Mensal
             *
             * O histórico antigo continuará mostrando:
             *   Preventiva de PDV
             */

            $table->string('preventive_type_name');

            $table->string('preventive_profile_name');

            $table->string('branch_name');

            $table->timestamps();

            /*
             * Uma preventiva possui um único snapshot inicial.
             */
            $table->unique('preventive_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventive_snapshots');
    }
};
