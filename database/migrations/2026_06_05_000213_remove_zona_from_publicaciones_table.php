<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('publicaciones', function (Blueprint $table) {
        $table->dropColumn('zona');
    });
}

public function down()
{
    Schema::table('publicaciones', function (Blueprint $table) {
        $table->string('zona')->nullable();
    });
}
};