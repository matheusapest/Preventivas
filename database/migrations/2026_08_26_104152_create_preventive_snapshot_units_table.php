<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preventive_snapshot_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('preventive_snapshot_id')
                ->constrained('preventive_snapshots')
                ->restrictOnDelete();

            $table->foreignId('operational_unit_id')
                ->constrained('operational_units')
                ->restrictOnDelete();

            $table->foreignId('operational_profile_id')
                ->constrained('operational_profiles')
                ->restrictOnDelete();

            $table->foreignId('unit_type_id')
                ->constrained('unit_types')
                ->restrictOnDelete();

            // Dados congelados da unidade no momento da preventiva.
            $table->string('operational_unit_name');
            $table->string('operational_unit_identifier');
            $table->string('operational_profile_name');
            $table->string('unit_type_name');

            $table->timestamps();

            $table->unique(
                [
                    'preventive_snapshot_id',
                    'operational_unit_id',
                ],
                'snapshot_unit_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preventive_snapshot_units');
    }
};
