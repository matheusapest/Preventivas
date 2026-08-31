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
        Schema::create('preventive_profile_rule_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('preventive_profile_rule_id')
                ->constrained('preventive_profile_rules')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('operational_unit_id')
                ->constrained('operational_units')
                ->cascadeOnUpdate();

            $table->timestamps();

            $table->unique(
                ['preventive_profile_rule_id', 'operational_unit_id'],
                'profile_rule_unit_unique'
            );

            $table->index('operational_unit_id');
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventive_profile_rule_units');
    }
};
