<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\StatusCycleEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('preventive_cycles', function (Blueprint $table) {

            $table->id();

            $table->foreignId('preventive_id')
                ->constrained('preventives')
                ->restrictOnDelete();

            $table->unsignedInteger('sequence')
                ->default(1);

            $table->enum(
                'status',
                array_column(StatusCycleEnum::cases(), 'value')
            )->comment('Status do ciclo');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('reviewed_at')
                ->nullable();

            $table->text('review_observation')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'preventive_id',
                'sequence',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventive_cycles');
    }
};
