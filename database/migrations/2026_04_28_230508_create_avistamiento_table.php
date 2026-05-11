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
        Schema::create('avistamientos', function (Blueprint $table) {
        $table->id();
        $table->text('descripcion')->nullable();
        $table->string('direccion')->nullable();
        $table->decimal('latitud',10,7)->nullable();
        $table->decimal('longitud',10,7)->nullable();

        $table->foreignId('usuario_id')->constrained()->onDelete('cascade');
        $table->foreignId('publicacion_id')->constrained('publicaciones')->onDelete('cascade');

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avistamiento');
    }
};
