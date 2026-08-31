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
        Schema::create('models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manufacturer_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->comment('ID do fabricante');
            $table->foreignId('category_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->comment('ID da categoria');
            $table->string('name', 150)
                ->comment('Nome do modelo');
            $table->boolean('active')
                ->default(true)
                ->comment('Indica se o modelo está ativo');

            $table->timestamps();
            $table->unique(
                ['manufacturer_id', 'name'],
                'models_manufacturer_name_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('models');
    }
};
