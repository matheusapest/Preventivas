<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('preventive_cycles')
            ->where('status', 'pending')
            ->update([
                'status' => 'new',
            ]);

        Schema::table('preventive_cycles', function (Blueprint $table) {
            $table->string('status')
                ->default('new')
                ->change();
        });
    }

    public function down(): void
    {
        DB::table('preventive_cycles')
            ->where('status', 'new')
            ->update([
                'status' => 'pending',
            ]);

        Schema::table('preventive_cycles', function (Blueprint $table) {
            $table->string('status')
                ->default('pending')
                ->change();
        });
    }
};
