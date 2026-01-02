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
            // Adicionando as colunas que faltam para a colheita
            $table->decimal('quantidade_colhida', 10, 2)->nullable(); // Ex: 1500.50 sacas
            $table->decimal('umidade', 5, 2)->nullable(); // Ex: 13.5%
            $table->date('data_colheita')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ciclos', function (Blueprint $table) {
            //
        });
    }
};
