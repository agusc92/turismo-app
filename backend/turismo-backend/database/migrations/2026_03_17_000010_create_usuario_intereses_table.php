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
        Schema::create('usuario_intereses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('info_usuario_id')->constrained('info_usuarios')->onDelete('cascade');
            $table->foreignId('tipo_id')->constrained('tipos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_intereses');
    }
};
