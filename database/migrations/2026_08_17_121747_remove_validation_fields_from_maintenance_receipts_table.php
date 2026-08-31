<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove os campos de validação do recebimento.
     */
    public function up(): void
    {
        Schema::table('maintenance_receipts', function (Blueprint $table) {

            /*
             * Remove a foreign key do usuário responsável
             * pela validação antes de remover a coluna.
             */
            $table->dropForeign([
                'validated_by',
            ]);

            /*
             * Remove os campos que agora pertencem
             * à tabela maintenance_validations.
             */
            $table->dropColumn([
                'validated_by',
                'validated_at',
                'tests_performed',
                'validation_status',
                'close_without_resend',
                'validation_observation',
            ]);
        });
    }

    /**
     * Restaura os campos de validação no recebimento.
     */
    public function down(): void
    {
        Schema::table('maintenance_receipts', function (Blueprint $table) {

            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('validated_at')
                ->nullable();

            $table->text('tests_performed')
                ->nullable();

            $table->enum('validation_status', [
                'approved',
                'rejected',
                'no_repair',
            ])
                ->nullable();

            $table->boolean('close_without_resend')
                ->default(false);

            $table->text('validation_observation')
                ->nullable();
        });
    }
};
