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
    Schema::table('pendaftaran_anggotas', function (Blueprint $table) {
        // Ini akan mencegah mahasiswa mendaftar di organisasi yang sama berkali-kali
        $table->unique(['id_user', 'id_organisasi']);
    });
}

public function down()
{
    Schema::table('pendaftaran_anggotas', function (Blueprint $table) {
        $table->dropUnique(['id_user', 'id_organisasi']);
    });
}
};
