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
        Schema::table('pedidos', function (Blueprint $table) {
              $table->json('detalles')->nullable();
              $table->integer('total')->default(0);
              $table->string('metodo_pago'); 
              $table->boolean('estado')->default(false);
            $table->integer('costo_domicilio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
             $table->dropColumn(['total', 'costo_domicilio','detalles']);
        });
    }
};
