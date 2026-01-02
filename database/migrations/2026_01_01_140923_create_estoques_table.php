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
    Schema::create('estoques', function (Blueprint $table) {
        $table->id();
        $table->foreignId('fazenda_id')->constrained('fazendas')->onDelete('cascade');
        $table->string('cultura'); // Soja, Milho
        $table->decimal('quantidade_kg', 12, 2)->default(0); // Saldo atual
        $table->string('unidade_medida')->default('kg');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estoques');
    }
};
