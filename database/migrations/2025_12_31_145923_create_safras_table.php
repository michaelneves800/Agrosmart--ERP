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
    Schema::create('safras', function (Blueprint $table) {
        $table->id();
        $table->string('nome');       // Ex: "Safra 2024/2025"
        $table->integer('ano_inicio'); // Ex: 2024
        $table->integer('ano_fim');    // Ex: 2025
        $table->date('data_inicio')->nullable(); // Opcional: data exata de início
        $table->date('data_fim')->nullable();    // Opcional: data exata de fim
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safras');
    }
};
