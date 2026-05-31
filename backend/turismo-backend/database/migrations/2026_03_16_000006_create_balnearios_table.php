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
        Schema::create('balnearios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('telefono')->nullable();
            $table->string('redesSociales')->nullable();
            $table->text('servicios')->nullable();
            $table->string('mail')->nullable();
            $table->text('accesibilidad')->nullable();
            $table->string('fecha_desde_hasta')->nullable();
            $table->text('imagen')->nullable(); // Cambiado de string a text
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balnearios');
    }
};
