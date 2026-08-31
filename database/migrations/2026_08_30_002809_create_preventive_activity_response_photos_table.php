<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preventive_activity_response_photos', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('preventive_activity_response_id');

            $table->string('path');

            $table->string('mime_type', 100);

            $table->unsignedBigInteger('size');

            $table->timestamp('captured_at');

            $table->timestamps();

            $table->unique(
                'preventive_activity_response_id',
                'parp_response_unique'
            );

            $table->foreign(
                'preventive_activity_response_id',
                'parp_response_fk'
            )
                ->references('id')
                ->on('preventive_activity_responses')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preventive_activity_response_photos');
    }
};
