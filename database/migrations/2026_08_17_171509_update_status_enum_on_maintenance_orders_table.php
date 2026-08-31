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
        Schema::table('maintenance_orders', function (Blueprint $table) {
            $table->enum(
                'status',
                array_column(
                    MaintenanceOrderStatus::cases(),
                    'value'
                )
            )
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_orders', function (Blueprint $table) {
            $table->enum(
                'status',
                [
                    MaintenanceOrderStatus::IN_REPAIR->value,
                    MaintenanceOrderStatus::IN_VALIDATION->value,
                    MaintenanceOrderStatus::COMPLETED->value,
                ]
            )
                ->change();
        });
    }
};
