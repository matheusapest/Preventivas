<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operational_units', function (Blueprint $table) {
            $table->id();

            $table->string('identifier');

            $table->foreignId('branch_id')
                ->constrained('branches')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('unit_type_id')
                ->constrained('unit_types')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('operational_profile_id')
                ->constrained('operational_profiles')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->unique([
                'branch_id',
                'identifier',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operational_units');
    }
};
