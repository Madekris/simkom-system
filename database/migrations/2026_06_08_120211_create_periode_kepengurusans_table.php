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
        Schema::create('periode_kepengurusans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_organisasi')->constrained('organisasis')->onDelete('cascade');
            $table->year('tahun_mulai');
            $table->year('tahun_selesai');
            $table->string('status_periode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_kepengurusans');
    }
};
