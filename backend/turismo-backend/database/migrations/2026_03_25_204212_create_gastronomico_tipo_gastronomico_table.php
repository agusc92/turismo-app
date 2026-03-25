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
    Schema::create('gastronomico_tipo_gastronomico', function (Blueprint $table) {
        $table->id();
        $table->foreignId('gastronomico_id')->constrained('gastronomicos')->onDelete('cascade');
        $table->foreignId('tipo_gastronomico_id')->constrained('tipo_gastronomicos')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastronomico_tipo_gastronomico');
    }
};
