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
            // 1. Remove a "corrente" (chave estrangeira)
            // O nome da constraint foi dado no erro: fazendas_empresa_id_foreign
            $table->dropForeign('fazendas_empresa_id_foreign');

            // 2. Agora sim, remove a coluna
            $table->dropColumn('empresa_id');
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
