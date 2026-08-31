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
        Schema::create('maintenance_validations', function (Blueprint $table) {

            $table->id();

            $table
                ->foreignId('maintenance_receipt_id')
                ->constrained('maintenance_receipts')
                ->cascadeOnDelete();

            $table
                ->foreignId('validated_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->dateTime('validated_at');

            $table->string('validation_status');

            $table->text('tests_performed');

            $table
                ->text('validation_observation')
                ->nullable();

            $table
                ->boolean('close_without_resend')
                ->default(false);

            $table->timestamps();

            $table->index(
                ['maintenance_receipt_id', 'validated_at'],
                'mv_receipt_validated_at_idx'
            );

            $table->index(
                'validation_status',
                'mv_validation_status_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_validations');
    }
};
