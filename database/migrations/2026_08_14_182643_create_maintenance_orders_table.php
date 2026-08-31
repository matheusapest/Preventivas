<?php

use App\Enums\MaintenanceOrderStatus;
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
        Schema::create('maintenance_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('equipment_id')
                ->constrained('equipments')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->comment('Id do equipamento');

            $table->enum(
                'status',
                array_column(
                    MaintenanceOrderStatus::cases(),
                    'value'
                )
            )->comment('Status da ordem de serviço');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_orders');
    }
};
