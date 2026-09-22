<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_google_id_to_users_table.php
public function up(): void
{
    Schema::table('usuarios', function (Blueprint $table) {
        $table->string('google_id')->nullable()->unique()->after('id');
        $table->string('password')->nullable()->change(); // Google users no tienen password
        $table->string('avatar')->nullable()->after('email');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            //
        });
    }
};
