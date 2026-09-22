<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            if (!Schema::hasColumn('publicaciones', 'vistas')) {
                $table->unsignedInteger('vistas')->default(0)->after('oculta_en');
            }
        });

        if (!Schema::hasTable('vistas_publicacion')) {
            Schema::create('vistas_publicacion', function (Blueprint $table) {
                $table->id();
                $table->foreignId('publicacion_id')->constrained('publicaciones')->onDelete('cascade');
                $table->string('ip', 45); // suficiente para IPv4 e IPv6
                $table->timestamps();

                // Un mismo IP no suma más de una vista a la misma publicación.
                $table->unique(['publicacion_id', 'ip']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vistas_publicacion');

        Schema::table('publicaciones', function (Blueprint $table) {
            if (Schema::hasColumn('publicaciones', 'vistas')) {
                $table->dropColumn('vistas');
            }
        });
    }
};