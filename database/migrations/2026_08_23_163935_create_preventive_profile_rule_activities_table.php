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
        Schema::create('preventive_profile_rule_activities', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('preventive_profile_rule_id');
            $table->unsignedBigInteger('activity_id');

            $table->timestamps();

            /*
             * Foreign keys
             */
            $table->foreign(
                'preventive_profile_rule_id',
                'ppra_rule_fk'
            )
                ->references('id')
                ->on('preventive_profile_rules')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign(
                'activity_id',
                'ppra_activity_fk'
            )
                ->references('id')
                ->on('activities')
                ->cascadeOnUpdate();

            /*
             * Índices
             */
            $table->unique(
                [
                    'preventive_profile_rule_id',
                    'activity_id',
                ],
                'ppra_rule_activity_unique'
            );

            $table->index(
                'activity_id',
                'ppra_activity_idx'
            );
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventive_profile_rule_activities');
    }
};
