<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operational_profile_categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('operational_profile_id')
                ->constrained('operational_profiles')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedInteger('quantity')->default(1);

            $table->timestamps();

            $table->unique(
                ['operational_profile_id', 'category_id'],
                'profile_category_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operational_profile_categories');
    }
};
