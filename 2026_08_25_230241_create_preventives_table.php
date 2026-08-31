<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela de instâncias de preventivas.
     */
    public function up(): void
    {
        Schema::create('preventives', function (Blueprint $table) {
            $table->id();

            /*
             * Filial onde a preventiva será executada.
             */
            $table->foreignId('branch_id')
                ->constrained()
                ->restrictOnDelete();

            /*
             * Tipo de preventiva utilizado pela instância.
             */
            $table->foreignId('preventive_type_id')
                ->constrained()
                ->restrictOnDelete();

            /*
             * Perfil utilizado como template para a configuração
             * inicial da preventiva.
             */
            $table->foreignId('preventive_profile_id')
                ->constrained()
                ->restrictOnDelete();

            /*
             * Usuário responsável pela execução.
             *
             * Pode ser um técnico ou administrador.
             */
            $table->foreignId('assigned_user_id')
                ->constrained('users')
                ->restrictOnDelete();

            /*
             * Usuário que criou a instância.
             */
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            /*
             * Data prevista para início da preventiva.
             */
            $table->date('start_date');

            /*
             * Prazo de conclusão.
             *
             * Ainda não utilizado pelo fluxo atual,
             * mas preparado para implementação futura.
             */
            $table->date('due_date')->nullable();

            /*
             * Status atual da preventiva.
             *
             * Valores serão definidos posteriormente através
             * de Enum de domínio.
             */
            $table->string('status');

            /*
             * Ciclo atual da preventiva.
             *
             * Começa no ciclo 1 e é incrementado quando
             * o gestor reprova a execução.
             */
            $table->unsignedInteger('current_cycle')
                ->default(1);

            /*
             * Momento em que o ciclo atual foi finalizado
             * pelo responsável.
             */
            $table->timestamp('completed_at')->nullable();

            /*
             * Momento em que o gestor aprovou a preventiva.
             */
            $table->timestamp('approved_at')->nullable();

            /*
             * Gestor responsável pela aprovação.
             */
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Remove a tabela de instâncias de preventivas.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventives');
    }
};
