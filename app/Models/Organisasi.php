<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// =========================================================================
// PERBAIKAN: Mengimpor semua class model yang digunakan dalam relasi
// =========================================================================
use App\Models\JenisOrganisasi;
use App\Models\AnggotaOrganisasi;
use App\Models\PendaftaranAnggota;

class Organisasi extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'organisasis'; 

    // Kolom yang diizinkan untuk Mass Assignment
    protected $fillable = [
        'id_jenis_organisasi',
        'nama',
        'deskripsi',
        'visi',
        'misi',
        'ad_art',
        'status',
    ];

    /**
     * Relasi ke model JenisOrganisasi
     */
    public function jenisOrganisasi(): BelongsTo
    {
        return $this->belongsTo(JenisOrganisasi::class, 'id_jenis_organisasi');
    }

    /**
     * Relasi ke model PendaftaranAnggota
     */
    public function pendaftaranAnggota(): HasMany
    {
        return $this->hasMany(PendaftaranAnggota::class, 'id_organisasi');
    }

    /**
     * Relasi ke model AnggotaOrganisasi (Semua Anggota)
     */
    public function anggotaOrganisasi(): HasMany
    {
        return $this->hasMany(AnggotaOrganisasi::class, 'id_organisasi');
    }

    /**
     * Relasi khusus untuk mengambil Ketua yang aktif
     */
/**
     * Relasi untuk mengambil data Mahasiswa yang menjadi Ketua aktif
     */
    public function ketua()
    {
        // Menggunakan hasOneThrough atau join manual agar langsung sampai ke tabel mahasiswa
        return $this->hasOne(AnggotaOrganisasi::class, 'id_organisasi')
            ->where('jabatan', 'Ketua')
            ->where('status', 'aktif');
            // Catatan: Jika ingin langsung ambil nama, di Blade gunakan $o->ketua->mahasiswa->nama
    }

    /**
     * Relasi khusus untuk mengambil Pengurus yang aktif
     */
    public function pengurus(): HasOne
    {
        return $this->hasOne(AnggotaOrganisasi::class, 'id_organisasi')
            ->where('jabatan', 'Pengurus')
            ->where('status', 'aktif');
    }

    /**
     * Relasi khusus untuk mengambil Bendahara yang aktif
     */
    public function bendahara(): HasOne
    {
        return $this->hasOne(AnggotaOrganisasi::class, 'id_organisasi')
            ->where('jabatan', 'Bendahara')
            ->where('status', 'aktif');
    }
}