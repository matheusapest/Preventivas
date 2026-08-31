<?php

declare(strict_types=1);

use App\Enums\StatusPreventiveEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Altera o campo de status das preventivas
     * para utilizar os estados definidos pelo domínio.
     */
    public function up(): void
    {
        Schema::table('preventives', function (Blueprint $table) {
            $table->enum(
                'status',
                array_column(StatusPreventiveEnum::cases(), 'value')
            )
                ->comment('Status da preventiva')
                ->change();
        });
    }

    /**
     * Reverte o campo de status para string.
     */
    public function down(): void
    {
        Schema::table('preventives', function (Blueprint $table) {
            $table->string('status')
                ->change();
        });
    }
};
