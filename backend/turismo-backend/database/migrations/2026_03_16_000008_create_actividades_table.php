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
            $table->text('descripcion')->nullable(); // Cambiado de string a text
            $table->text('redes_sociales')->nullable(); // Cambiado de string a text
            $table->text('web')->nullable(); // Cambiado de string a text
            $table->text('mail')->nullable(); // Cambiado de string a text
            $table->text('telefono')->nullable(); // Cambiado de string a text
            $table->text('imagen')->nullable(); // Cambiado de string a text
            $table->foreignId('tipo_id')->constrained('tipos')->onDelete('cascade');
            $table->text('dias_y_horarios')->nullable(); // Cambiado de string a text
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
