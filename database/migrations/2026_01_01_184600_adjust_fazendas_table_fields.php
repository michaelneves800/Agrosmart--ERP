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
        // Torna a área opcional para não travar o cadastro da "Fazenda Palmeira"
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
