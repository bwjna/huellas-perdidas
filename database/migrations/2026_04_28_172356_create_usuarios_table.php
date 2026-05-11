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
        Schema::create('usuarios', function (Blueprint $table) {
        $table->id();
        $table->enum('rol', ['usuario','admin'])->default('usuario');
        $table->string('nombre');
        $table->string('apellido');
        $table->string('nombre_usuario')->unique();
        $table->string('email')->unique();
        $table->string('password');
        $table->string('foto_perfil')->nullable();
        $table->string('telefono')->nullable();
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
