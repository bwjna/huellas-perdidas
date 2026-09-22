<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * El tamaño "chico" pasa a llamarse "pequeño" en todo el sistema.
     * Los registros existentes se convierten para no perder datos.
     */
    public function up(): void
    {
        // 1. Habilito ambos valores temporariamente
        DB::statement("ALTER TABLE mascotas MODIFY tamano ENUM('pequeño','chico','mediano','grande') NULL");

        // 2. Migro los datos existentes
        DB::table('mascotas')->where('tamano', 'chico')->update(['tamano' => 'pequeño']);

        // 3. Dejo solo el enum definitivo
        DB::statement("ALTER TABLE mascotas MODIFY tamano ENUM('pequeño','mediano','grande') NULL");
    }

    public function down(): void
    {
        // Reverso: "pequeño" vuelve a "chico"
        DB::statement("ALTER TABLE mascotas MODIFY tamano ENUM('pequeño','chico','mediano','grande') NULL");
        DB::table('mascotas')->where('tamano', 'pequeño')->update(['tamano' => 'chico']);
        DB::statement("ALTER TABLE mascotas MODIFY tamano ENUM('chico','mediano','grande') NULL");
    }
};