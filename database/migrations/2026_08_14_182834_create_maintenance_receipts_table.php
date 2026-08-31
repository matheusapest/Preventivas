<?php

use App\Enums\MaintenanceValidationStatus;
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
        Schema::create('maintenance_receipts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('maintenance_shipment_id')
                ->unique()
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('received_by')
                ->constrained('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->timestamp('received_at');

            $table->string('invoice_number', 50)
                ->nullable();

            $table->text('receiving_observation')
                ->nullable();

            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->timestamp('validated_at')
                ->nullable();

            $table->text('tests_performed')
                ->nullable();

            $table->enum(
                'validation_status',
                array_column(
                    MaintenanceValidationStatus::cases(),
                    'value'
                )
            )->nullable();

            $table->text('validation_observation')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_receipts');
    }
};
