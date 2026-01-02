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
    Schema::create('insumos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('fazenda_id')->constrained('fazendas')->onDelete('cascade');
        
        $table->string('nome'); // Ex: Roundup, Semente SoyTech 500
        $table->string('categoria'); // Defensivo, Fertilizante, Semente, Combustivel
        
        $table->decimal('quantidade', 10, 2)->default(0); // Estoque atual
        $table->string('unidade'); // L, Kg, Un, Sc
        
        $table->decimal('custo_medio', 10, 2)->nullable(); // Preço médio pago
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};
