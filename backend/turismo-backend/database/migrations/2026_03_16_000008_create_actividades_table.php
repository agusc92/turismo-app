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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('descripcion')->nullable();
            $table->string('redes_sociales')->nullable();
            $table->string('web')->nullable();
            $table->string('mail')->nullable();
            $table->string('telefono')->nullable();
            $table->string('imagen')->nullable();
            $table->foreignId('tipo_id')->constrained('tipos')->onDelete('cascade');
            $table->string('dias_y_horarios')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividads');
    }
};
