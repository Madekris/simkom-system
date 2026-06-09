<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_organisasi')->constrained('organisasis');
            $table->foreignId('id_periode')->constrained('periode_kepengurusans');
            $table->string('judul_kegiatan');
            $table->text('deskripsi');
            $table->date('tanggal_kegiatan');
            $table->time('waktu_kegiatan');
            $table->string('lokasi');
            $table->integer('kuota_peserta');
            $table->string('status');
            $table->text('evaluasi_kegiatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
