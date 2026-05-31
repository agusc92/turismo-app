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
        Schema::create('complejos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('mail')->nullable();
            $table->string('redesSociales')->nullable();
            $table->string('telefono')->nullable();
            $table->text('servicio')->nullable(); // Usamos text para servicios que pueden ser más largos
            $table->text('adicional')->nullable(); // Usamos text para información adicional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complejos');
    }
};
