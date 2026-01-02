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
    Schema::create('vendas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('fazenda_id')->constrained('fazendas')->onDelete('cascade');
        
        $table->string('cultura'); // Soja, Milho
        $table->string('comprador'); // Cooperativa, Cargill, Vizinho
        
        $table->decimal('quantidade_kg', 12, 2); // Quanto vendeu
        $table->decimal('preco_unitario', 10, 2); // Preço por saca/kg
        $table->decimal('valor_total', 15, 2); // Total da nota
        
        $table->date('data_venda');
        $table->text('observacoes')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendas');
    }
};
