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
        Schema::create('transfers', function (Blueprint $table) {

            $table->id();

            $table->foreignId('equipment_id')
                ->constrained('equipments')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->comment('Equipamento transferido');

            $table->foreignId('origin_branch_id')
                ->constrained('branches')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->comment('Filial de origem');

            $table->foreignId('destination_branch_id')
                ->constrained('branches')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->comment('Filial de destino');

            $table->foreignId('sent_by')
                ->constrained('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->comment('Usuário responsável pelo envio');

            $table->dateTime('sent_at')
                ->comment('Data e hora do envio');

            $table->foreignId('received_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate()
                ->comment('Usuário responsável pelo recebimento');

            $table->dateTime('received_at')
                ->nullable()
                ->comment('Data e hora do recebimento');

            $table->enum('status', [
                'sent',
                'received',
            ])->default('sent')
              ->comment('Status da transferência');

            $table->text('observation')
                ->nullable()
                ->comment('Observações da transferência');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
