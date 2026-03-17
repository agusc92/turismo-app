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
        Schema::create('info_usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('ciudad')->nullable();
            $table->integer('edad')->nullable();
            $table->integer('estadia')->nullable();
            $table->integer('integrantes')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_usuarios');
    }
};
