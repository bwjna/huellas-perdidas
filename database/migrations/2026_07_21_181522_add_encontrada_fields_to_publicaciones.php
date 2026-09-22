<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            if (!Schema::hasColumn('publicaciones', 'fecha_encontrada')) {
                $table->date('fecha_encontrada')->nullable()->after('fecha_evento');
            }
            if (!Schema::hasColumn('publicaciones', 'ubicacion_encontrada')) {
                $table->string('ubicacion_encontrada')->nullable()->after('fecha_encontrada');
            }
            if (!Schema::hasColumn('publicaciones', 'contacto')) {
                $table->string('contacto')->nullable()->after('ubicacion_encontrada');
            }
        });
    }

    public function down(): void
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            if (Schema::hasColumn('publicaciones', 'fecha_encontrada')) {
                $table->dropColumn('fecha_encontrada');
            }
            if (Schema::hasColumn('publicaciones', 'ubicacion_encontrada')) {
                $table->dropColumn('ubicacion_encontrada');
            }
            if (Schema::hasColumn('publicaciones', 'contacto')) {
                $table->dropColumn('contacto');
            }
        });
    }
};
