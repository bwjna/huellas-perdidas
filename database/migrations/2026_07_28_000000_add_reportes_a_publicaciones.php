<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            if (!Schema::hasColumn('publicaciones', 'reportes')) {
                $table->unsignedInteger('reportes')->default(0)->after('estado');
            }
            if (!Schema::hasColumn('publicaciones', 'oculta_en')) {
                $table->timestamp('oculta_en')->nullable()->after('reportes');
            }
        });

        if (!Schema::hasTable('reportes_publicacion')) {
            Schema::create('reportes_publicacion', function (Blueprint $table) {
                $table->id();
                $table->foreignId('publicacion_id')->constrained('publicaciones')->onDelete('cascade');
                $table->string('ip', 45); // suficiente para IPv4 e IPv6
                $table->timestamps();

                // Un mismo IP no puede reportar la misma publicación dos veces.
                $table->unique(['publicacion_id', 'ip']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes_publicacion');

        Schema::table('publicaciones', function (Blueprint $table) {
            if (Schema::hasColumn('publicaciones', 'reportes')) {
                $table->dropColumn('reportes');
            }
            if (Schema::hasColumn('publicaciones', 'oculta_en')) {
                $table->dropColumn('oculta_en');
            }
        });
    }
};