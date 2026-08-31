<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela de respostas das atividades preventivas.
     */
    public function up(): void
    {
        Schema::create('preventive_activity_responses', function (Blueprint $table) {
            $table->id();

            /*
             * Unidade da preventiva dentro do ciclo.
             */
            $table->foreignId('preventive_cycle_unit_id')
                ->constrained('preventive_cycle_units')
                ->cascadeOnDelete();

            /*
             * Atividade congelada no snapshot.
             */
            $table->foreignId('snapshot_rule_activity_id')
                ->constrained('preventive_snapshot_rule_activities');

            /*
             * Resultado da inspeção.
             *
             * Exemplos:
             * conforme
             * nao_conforme
             */
            $table->string('result', 30);

            /*
             * Situação final da ocorrência.
             *
             * Exemplos:
             * resolved
             * pending
             *
             * Utilizado principalmente quando
             * o resultado for não conforme.
             */
            $table->string('final_status', 30)->nullable();

            /*
             * Observação do técnico.
             */
            $table->text('observation')->nullable();

            /*
             * Dados específicos da resposta.
             *
             * O conteúdo depende do tipo da atividade.
             */
            $table->json('response_data')->nullable();

            /*
             * Momento em que o técnico iniciou a atividade.
             */
            $table->timestamp('started_at')->nullable();

            /*
             * Momento em que o técnico respondeu/finalizou a atividade.
             */
            $table->timestamp('answered_at')->nullable();

            $table->timestamps();

            /*
             * Uma atividade só pode possuir uma resposta
             * por unidade dentro de um ciclo.
             */
            $table->unique(
                [
                    'preventive_cycle_unit_id',
                    'snapshot_rule_activity_id',
                ],
                'par_cycle_unit_activity_unique'
            );

            /*
             * Índice para consultas das respostas
             * por unidade e resultado.
             */
            $table->index(
                [
                    'preventive_cycle_unit_id',
                    'result',
                ],
                'par_cycle_unit_result_idx'
            );

            /*
             * Índice para consultas das respostas
             * por atividade e resultado.
             */
            $table->index(
                [
                    'snapshot_rule_activity_id',
                    'result',
                ],
                'par_activity_result_idx'
            );
        });
    }

    /**
     * Remove a tabela.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventive_activity_responses');
    }
};
