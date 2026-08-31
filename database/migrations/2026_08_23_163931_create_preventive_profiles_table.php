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
        Schema::create('preventive_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('preventive_type_id')
                ->constrained('preventive_types')
                ->cascadeOnUpdate();

            $table->string('name', 150);

            $table->text('description')
                ->nullable();

            $table->boolean('active')
                ->default(true);

            $table->timestamps();

            $table->unique([
                'preventive_type_id',
                'name',
            ]);
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventive_profiles');
    }
};
