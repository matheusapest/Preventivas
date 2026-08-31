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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('preventive_type_id')
                ->constrained('preventive_types')
                ->cascadeOnDelete();

            $table->string('name');
            $table->text('description')->nullable();

            $table->string('type');

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->index([
                'preventive_type_id',
                'active',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
