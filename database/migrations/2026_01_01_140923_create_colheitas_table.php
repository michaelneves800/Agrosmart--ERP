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
    Schema::create('colheitas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('ciclo_id')->constrained('ciclos')->onDelete('cascade'); // Liga ao plantio
        $table->decimal('quantidade_colhida', 10, 2); // Quanto saiu
        $table->decimal('umidade', 5, 2)->nullable(); // Dado técnico importante
        $table->date('data_colheita');
        $table->text('observacoes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colheitas');
    }
};
