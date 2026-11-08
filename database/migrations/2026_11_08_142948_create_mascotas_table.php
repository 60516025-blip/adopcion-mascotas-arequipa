<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('albergue_id')->constrained('albergues')->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->enum('especie', ['perro', 'gato']);
            $table->string('raza', 100)->nullable();
            $table->unsignedSmallInteger('edad_meses');
            $table->enum('tamano', ['pequeno', 'mediano', 'grande']);
            $table->enum('genero', ['macho', 'hembra']);
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['disponible', 'en_proceso', 'adoptada'])->default('disponible');
            $table->string('foto_url')->nullable();
            $table->timestamps();

            // Índices para el catálogo con filtros
            $table->index('especie');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};