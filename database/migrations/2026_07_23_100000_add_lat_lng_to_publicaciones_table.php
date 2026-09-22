<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            if (!Schema::hasColumn('publicaciones', 'lat')) {
                $table->decimal('lat', 10, 7)->nullable()->after('zona');
            }
            if (!Schema::hasColumn('publicaciones', 'lng')) {
                $table->decimal('lng', 10, 7)->nullable()->after('lat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            if (Schema::hasColumn('publicaciones', 'lat')) {
                $table->dropColumn('lat');
            }
            if (Schema::hasColumn('publicaciones', 'lng')) {
                $table->dropColumn('lng');
            }
        });
    }
};