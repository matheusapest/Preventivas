<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preventive_snapshot_rule_activities', function (Blueprint $table) {

            $table->id();

            /*
             * Regra congelada à qual esta atividade pertence.
             */
            $table->foreignId('preventive_snapshot_rule_id')
                ->constrained(
                    'preventive_snapshot_rules',
                    'id',
                    'snapshot_rule_activity_rule_fk'
                )
                ->restrictOnDelete();

            /*
             * ID da atividade original.
             *
             * Não é FK propositalmente.
             * O snapshot precisa continuar existindo mesmo que
             * a atividade original seja removida posteriormente.
             */
            $table->unsignedBigInteger('activity_id');

            /*
             * Dados congelados da atividade no momento
             * da criação da preventiva.
             */
            $table->string('activity_name');

            $table->text('activity_description')->nullable();

            $table->string('activity_type', 50);

            $table->timestamps();

            /*
             * Uma atividade só pode aparecer uma vez
             * dentro da mesma regra congelada.
             */
            $table->unique(
                [
                    'preventive_snapshot_rule_id',
                    'activity_id',
                ],
                'snapshot_rule_activity_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preventive_snapshot_rule_activities');
    }
};
