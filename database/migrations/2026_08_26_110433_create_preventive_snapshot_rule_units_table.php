<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preventive_snapshot_rule_units', function (Blueprint $table) {

            $table->id();

            /*
             * Snapshot da regra à qual esta unidade pertence.
             *
             * É uma FK porque este registro faz parte da estrutura
             * do snapshot. Se o snapshot for removido, seus registros
             * também podem ser removidos.
             */
            $table->foreignId('preventive_snapshot_rule_id')
                ->constrained(
                    'preventive_snapshot_rules',
                    'id',
                    'snapshot_rule_unit_rule_fk'
                )
                ->restrictOnDelete();

            /*
             * ID da unidade operacional original.
             *
             * Não é FK propositalmente.
             *
             * O snapshot precisa continuar representando a realidade
             * existente no momento da criação da preventiva mesmo que
             * a unidade original seja removida posteriormente.
             */
            $table->unsignedBigInteger('operational_unit_id');

            /*
             * Dados congelados da unidade no momento da criação
             * da preventiva.
             */
            $table->string('operational_unit_name');
            $table->string('operational_unit_identifier');

            $table->timestamps();

            /*
             * Uma unidade só pode aparecer uma vez dentro
             * da mesma regra congelada.
             */
            $table->unique(
                [
                    'preventive_snapshot_rule_id',
                    'operational_unit_id',
                ],
                'snapshot_rule_unit_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preventive_snapshot_rule_units');
    }
};
