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
        // Mengubah tipe kolom string menjadi enum
        $table->enum('status', ['pending', 'aktif', 'ditolak'])->default('pending')->change();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran_anggotas', function (Blueprint $table) {
            //
        });
    }
};
