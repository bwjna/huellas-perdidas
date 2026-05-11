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
        Schema::create('publicaciones', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->string('imagen')->nullable();
        $table->enum('estado', ['perdido','encontrado','resuelto'])->default('perdido');
        $table->date('fecha_evento')->nullable();
        $table->string('zona');
        $table->text('descripcion')->nullable();

        $table->foreignId('usuario_id')->constrained()->onDelete('cascade');
        $table->foreignId('mascota_id')->constrained()->onDelete('cascade');

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicacion');
    }
};
