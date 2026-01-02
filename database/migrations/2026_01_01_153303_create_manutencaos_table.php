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
    Schema::create('manutencoes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('maquina_id')->constrained('maquinas')->onDelete('cascade');
        
        $table->string('descricao'); // Ex: Troca de Óleo, Pneu furado
        $table->date('data_manutencao');
        $table->decimal('custo', 10, 2); // Valor do conserto
        $table->decimal('horimetro_na_data', 10, 2)->nullable(); // Com quantas horas estava
        
        $table->string('tipo')->default('preventiva'); // preventiva ou corretiva
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manutencaos');
    }
};
