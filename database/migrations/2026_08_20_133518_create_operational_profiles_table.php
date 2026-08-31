<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operational_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unit_type_id')
                ->constrained('unit_types')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('name');

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->unique([
                'unit_type_id',
                'name',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operational_profiles');
    }
};
