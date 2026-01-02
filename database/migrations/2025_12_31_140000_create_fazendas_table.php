<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
   Schema::create('fazendas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('empresa_id')->constrained()->cascadeOnDelete();
    $table->string('nome');
    $table->decimal('area_total_ha', 10, 2);
    $table->string('municipio');
    $table->char('estado', 2);
    $table->timestamps();
});

}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fazendas');
    }
};
