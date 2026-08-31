<?php

use App\Enums\MaintenanceShipmentStatus;
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
        Schema::create('maintenance_shipments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('maintenance_order_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedInteger('sequence');
            // o metodo unsignedInteger é um metodo que serve para campos que nao podem receber
            // valores negativos, por isso nao usamos o integer nele por conta que ele aceitaria um
            // valor negativo

            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('origin_branch_id')
                ->constrained('branches')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('sent_by')
                ->constrained('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->timestamp('sent_at');

            $table->string('invoice_number', 50)
                ->nullable();

            $table->text('defect_description');

            $table->text('observation')
                ->nullable();

            $table->enum(
                'status',
                array_column(
                    MaintenanceShipmentStatus::cases(),
                    'value'
                )
            );

            $table->unique([
                'maintenance_order_id',
                'sequence',
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_shipments');
    }
};
