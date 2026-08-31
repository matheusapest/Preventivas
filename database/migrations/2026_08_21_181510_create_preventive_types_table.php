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
        Schema::create('preventive_types', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unit_type_id')
                ->constrained('unit_types')
                ->restrictOnDelete();

            $table->string('name', 150);

            $table->text('description')
                ->nullable();

            $table->boolean('active')
                ->default(true);

            $table->timestamps();

            $table->unique([
                'unit_type_id',
                'name',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventive_types');
    }
};
