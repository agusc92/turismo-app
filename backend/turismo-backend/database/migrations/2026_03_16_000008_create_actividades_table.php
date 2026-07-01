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
            $table->text('descripcion')->nullable();
            $table->text('redes_sociales')->nullable();
            $table->text('web')->nullable();
            $table->text('mail')->nullable();
            $table->text('telefono')->nullable();
            $table->text('imagen')->nullable();
            $table->foreignId('tipo_id')->constrained('tipos')->onDelete('cascade');
            $table->text('dias_y_horarios')->nullable();
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
        Schema::dropIfExists('actividades');
    }
};
