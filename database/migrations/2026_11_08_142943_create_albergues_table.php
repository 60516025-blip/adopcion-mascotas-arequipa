<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('albergues', function (Blueprint $table) {
            $table->id();
            // Usuario con rol "albergue" que administra este albergue
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nombre', 150);
            $table->string('distrito', 100);
            $table->text('descripcion')->nullable();
            // El administrador valida el albergue antes de que pueda publicar
            $table->boolean('validado')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('albergues');
    }
};