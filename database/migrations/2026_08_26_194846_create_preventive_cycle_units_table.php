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
        Schema::create('preventive_cycle_unit_activities', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('preventive_cycle_unit_id');

            $table->unsignedBigInteger('snapshot_rule_activity_id');

            $table->timestamps();

            /*
             * Foreign keys
             * Nomes explícitos para evitar ultrapassar
             * o limite de 64 caracteres do MySQL/MariaDB.
             */
            $table->foreign(
                'preventive_cycle_unit_id',
                'pcua_cycle_unit_fk'
            )
                ->references('id')
                ->on('preventive_cycle_units')
                ->cascadeOnDelete();

            $table->foreign(
                'snapshot_rule_activity_id',
                'pcua_snapshot_activity_fk'
            )
                ->references('id')
                ->on('preventive_snapshot_rule_activities')
                ->restrictOnDelete();

            /*
             * Uma mesma atividade do snapshot não pode
             * ser vinculada duas vezes à mesma unidade do ciclo.
             */
            $table->unique(
                [
                    'preventive_cycle_unit_id',
                    'snapshot_rule_activity_id',
                ],
                'pcua_unit_activity_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventive_cycle_unit_activities');
    }
};
