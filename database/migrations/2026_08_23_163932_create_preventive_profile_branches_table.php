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
        Schema::create('preventive_profile_branches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('preventive_profile_id')
                ->constrained('preventive_profiles')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnUpdate();

            $table->timestamps();

            $table->unique(
                ['preventive_profile_id', 'branch_id'],
                'profile_branch_unique'
            );

            $table->index('branch_id');
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventive_profile_branches');
    }
};
