<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preventive_snapshot_rules', function (Blueprint $table) {
            $table->id();

            /*
             * Snapshot ao qual esta regra pertence.
             */
            $table->foreignId('preventive_snapshot_id')
                ->constrained('preventive_snapshots')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
             * ID da regra original no perfil.
             *
             * É mantido apenas como referência histórica.
             * Não é uma FK propositalmente, pois a regra original
             * poderá ser removida/inativada no futuro sem afetar
             * o histórico da preventiva.
             */
            $table->unsignedBigInteger('preventive_profile_rule_id');

            /*
             * Tipo da regra no momento da criação da preventiva.
             *
             * Exemplos:
             * - all
             * - specific
             */
            $table->string('rule_type', 20);

            /*
             * Timestamps do registro congelado.
             */
            $table->timestamps();

            /*
             * Uma regra original só pode ser registrada uma vez
             * dentro do mesmo snapshot.
             */
            $table->unique([
                'preventive_snapshot_id',
                'preventive_profile_rule_id',
            ], 'snapshot_rule_unique');

            /*
             * Facilita consultas por tipo de regra.
             */
            $table->index(
                ['preventive_snapshot_id', 'rule_type'],
                'snapshot_rule_type_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preventive_snapshot_rules');
    }
};
