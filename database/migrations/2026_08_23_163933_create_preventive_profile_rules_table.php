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
        Schema::create('preventive_profile_rules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('preventive_profile_branch_id')
                ->constrained('preventive_profile_branches')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('rule_type', 20);

            $table->timestamps();

            $table->index(
                ['preventive_profile_branch_id', 'rule_type'],
                'profile_rule_type_index'
            );
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventive_profile_rules');
    }
};
