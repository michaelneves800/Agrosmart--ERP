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
    Schema::create('talhoes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('fazenda_id')->constrained('fazendas')->onDelete('cascade'); // Link com a Fazenda
        $table->string('nome');       // Ex: "Talhão do Rio"
        $table->decimal('area_hectares', 10, 2); // <--- ESTA É A COLUNA QUE FALTAVA
        $table->string('tipo_solo')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talhoes');
    }
};
