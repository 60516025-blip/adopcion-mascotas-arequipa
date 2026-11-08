<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fichas_medicas', function (Blueprint $table) {
            $table->id();
            // Una ficha por mascota
            $table->foreignId('mascota_id')->unique()->constrained('mascotas')->cascadeOnDelete();
            $table->boolean('esterilizado')->default(false);
            $table->boolean('vacuna_antirrabica')->default(false);
            $table->boolean('vacuna_quintuple')->default(false);
            $table->boolean('desparasitado')->default(false);
            $table->text('observaciones')->nullable();
            $table->date('fecha_ultima_revision')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fichas_medicas');
    }
};