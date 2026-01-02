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
    Schema::create('maquinas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('fazenda_id')->constrained('fazendas')->onDelete('cascade'); // Assumindo que pertence à fazenda
        
        $table->string('nome'); // Ex: John Deere 7230
        $table->string('tipo'); // Trator, Colheitadeira, Pulverizador, Caminhonete
        $table->string('modelo')->nullable();
        $table->integer('ano_fabricacao')->nullable();
        
        $table->decimal('horimetro_atual', 10, 2)->default(0); // O "odômetro" do trator
        $table->string('status')->default('disponivel'); // disponivel, manutencao, em_uso
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maquinas');
    }
};
