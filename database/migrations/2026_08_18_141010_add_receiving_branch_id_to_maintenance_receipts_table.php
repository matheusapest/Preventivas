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
        Schema::table('maintenance_receipts', function (Blueprint $table) {
            $table->foreignId('receiving_branch_id')
                ->nullable()
                ->constrained('branches')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_receipts', function (Blueprint $table) {
            $table->dropForeign([
                'receiving_branch_id',
            ]);

            $table->dropColumn(
                'receiving_branch_id'
            );
        });
    }
};
