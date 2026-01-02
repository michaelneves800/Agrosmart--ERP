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
    Schema::table('ciclos', function (Blueprint $table) {
        $table->date('data_fim')->nullable(); // Data real da colheita
        $table->decimal('produtividade_real', 10, 2)->nullable(); // sc/ha real colhido
    });
}

public function down(): void
{
    Schema::table('ciclos', function (Blueprint $table) {
        $table->dropColumn(['data_fim', 'produtividade_real']);
    });
}
};
