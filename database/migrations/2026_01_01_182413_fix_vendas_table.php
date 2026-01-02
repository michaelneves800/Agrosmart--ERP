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
        Schema::table('vendas', function (Blueprint $table) {
            // Adiciona a coluna 'quantidade' se ela não existir
            if (!Schema::hasColumn('vendas', 'quantidade')) {
                $table->decimal('quantidade', 10, 2)->after('cultura'); 
            }
            
            // Adiciona 'preco_unitario' se não existir
            if (!Schema::hasColumn('vendas', 'preco_unitario')) {
                $table->decimal('preco_unitario', 10, 2)->after('quantidade');
            }

            // Adiciona 'comprador' se não existir
            if (!Schema::hasColumn('vendas', 'comprador')) {
                $table->string('comprador')->nullable()->after('valor_total');
            }
            
            // Adiciona 'data_venda' se não existir
            if (!Schema::hasColumn('vendas', 'data_venda')) {
                $table->date('data_venda')->nullable()->after('comprador');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendas', function (Blueprint $table) {
            //
        });
    }
};
