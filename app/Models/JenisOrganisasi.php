<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisOrganisasi extends Model
{
    use HasFactory;

    // Mengunci nama tabel sesuai phpMyAdmin kamu
    protected $table = 'jenis_organisasis';

    protected $fillable = [
        'nama_jenis', 
    ];

    /**
     * Relasi ke model Organisasi (Satu jenis bisa memiliki banyak organisasi)
     */
    public function organisasis(): HasMany
    {
        return $this->hasMany(Organisasi::class, 'id_jenis_organisasi');
    }
}