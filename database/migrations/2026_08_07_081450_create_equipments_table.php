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
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('branch_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->comment('Filial onde o equipamento está instalado');

            $table->foreignId('equipment_model_id')
                ->constrained('models')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->comment('Modelo do equipamento');

            $table->string('name', 150)
                ->comment('Nome amigável do equipamento');

            $table->string('asset_number', 50)
                ->unique()
                ->nullable()
                ->comment('Número patrimonial');

            $table->string('serial_number', 100)
                ->unique()
                ->nullable()
                ->comment('Número de série do fabricante');

            $table->string('internal_tag', 50)
                ->nullable()
                ->comment('Etiqueta interna do equipamento');

            $table->text('description')
                ->nullable()
                ->comment('Observações do equipamento');

            $table->boolean('active')
                ->default(true)
                ->comment('Indica se o equipamento está ativo');

            $table->index('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
