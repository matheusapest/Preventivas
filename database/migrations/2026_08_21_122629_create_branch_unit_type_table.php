<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_unit_type', function (Blueprint $table) {
            $table->id();

            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();

            $table->foreignId('unit_type_id')
                ->constrained('unit_types')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(
                ['branch_id', 'unit_type_id'],
                'branch_unit_type_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_unit_type');
    }
};
