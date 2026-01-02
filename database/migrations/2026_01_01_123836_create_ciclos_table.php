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
    Schema::create('ciclos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('talhao_id')->constrained('talhoes')->onDelete('cascade');
        $table->string('cultura'); // Soja, Milho, etc.
        $table->string('variedade')->nullable(); // Nome da semente
        $table->date('data_plantio');
        $table->date('estimativa_colheita')->nullable();
        $table->enum('status', ['plantado', 'colhido', 'cancelado'])->default('plantado');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciclos');
    }
};
