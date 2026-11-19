<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imagenes', function (Blueprint $table) {
            $table->id();
            $table->string('actividades')->nullable();
            $table->string('gastronomicos')->nullable();
            $table->string('eventos')->nullable();
            $table->string('alojamientos')->nullable();
            $table->string('complejos')->nullable();
            $table->string('balnearios')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imagenes');
    }
};
