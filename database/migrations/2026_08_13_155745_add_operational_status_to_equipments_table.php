<?php

use App\Enums\OperationalStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipments', function (Blueprint $table): void {
            $table
                ->string('operational_status')
                ->default(OperationalStatus::KIT_BACKUP->value)
                ->after('active');
        });
    }

    public function down(): void
    {
        Schema::table('equipments', function (Blueprint $table): void {
            $table->dropColumn('operational_status');
        });
    }
};
