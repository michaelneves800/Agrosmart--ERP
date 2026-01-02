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
    Schema::table('fazendas', function (Blueprint $table) {
        // 1. Renomeia municipio para cidade se existir
        if (Schema::hasColumn('fazendas', 'municipio')) {
            $table->renameColumn('municipio', 'cidade');
        }

        // 2. Garante que cidade e estado aceitem valores
        $table->string('cidade')->nullable()->change();
        $table->string('estado')->nullable()->change();

        // 3. Garante que a área não seja obrigatória no cadastro inicial
        if (Schema::hasColumn('fazendas', 'area_total_ha')) {
            $table->decimal('area_total_ha', 10, 2)->nullable()->change();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fazendas', function (Blueprint $table) {
            //
        });
    }
};
