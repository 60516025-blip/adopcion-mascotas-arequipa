<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mascota_id')->constrained('mascotas')->cascadeOnDelete();
            $table->foreignId('adoptante_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('numero_personas');
            $table->enum('tipo_vivienda', ['propia', 'alquilada']);
            $table->boolean('tiene_patio')->default(false);
            // null = no aplica (vivienda propia)
            $table->boolean('permiso_mascotas')->nullable();
            $table->text('experiencia_previa')->nullable();
            $table->boolean('acepta_compromiso')->default(false);
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            // Mensaje del albergue al aprobar o rechazar
            $table->text('respuesta')->nullable();
            $table->timestamps();

            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};