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
            $table->boolean('close_without_resend')
                ->default(false)
                ->after('validation_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_receipts', function (Blueprint $table) {
            $table->dropColumn('close_without_resend');
        });
    }
};
