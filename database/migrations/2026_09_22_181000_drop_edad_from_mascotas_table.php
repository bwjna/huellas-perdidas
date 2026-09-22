<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * La edad ya no se usa en ningún lugar del sistema: se elimina de la tabla.
     */
    public function up(): void
    {
        Schema::table('mascotas', function (Blueprint $table) {
            $table->dropColumn('edad');
        });
    }

    public function down(): void
    {
        // Reverso por si alguna vez se necesita volver a tener la columna
        Schema::table('mascotas', function (Blueprint $table) {
            $table->integer('edad')->nullable()->after('nombre');
        });
    }
};