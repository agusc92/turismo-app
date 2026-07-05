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
        Schema::create('alojamientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('telefono')->nullable();
            $table->string('redesSociales')->nullable();
            $table->string('paginaWeb')->nullable();
            $table->string('mail')->nullable();
            $table->boolean('mascotas')->default(false);
            $table->text('periodoApertura')->nullable();
            $table->string('imagen')->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->boolean('habilitado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alojamientos');
    }
};
