<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Models\JenisOrganisasi;
use App\Models\AnggotaOrganisasi;
use App\Models\PendaftaranAnggota;

class Organisasi extends Model
{
    use HasFactory, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('manajemen_organisasi') // Dikelola oleh Admin / Pengurus
            ->setDescriptionForEvent(fn(string $eventName) => "Organisasi '{$this->nama}' telah di {$eventName}");
    }

    public function jenisOrganisasi(): BelongsTo
    {
        return $this->belongsTo(JenisOrganisasi::class, 'id_jenis_organisasi');
    }

    public function pendaftaranAnggota(): HasMany
    {
        return $this->hasMany(PendaftaranAnggota::class, 'id_organisasi');
    }

    public function anggotaOrganisasi(): HasMany
    {
        return $this->hasMany(AnggotaOrganisasi::class, 'id_organisasi');
    }

    public function ketua()
    {
        return $this->hasOne(AnggotaOrganisasi::class, 'id_organisasi')
            ->where('jabatan', 'Ketua')
            ->where('status', 'aktif');
    }

    public function pengurus(): HasOne
    {
        return $this->hasOne(AnggotaOrganisasi::class, 'id_organisasi')
            ->where('jabatan', 'Pengurus')
            ->where('status', 'aktif');
    }

    public function bendahara(): HasOne
    {
        return $this->hasOne(AnggotaOrganisasi::class, 'id_organisasi')
            ->where('jabatan', 'Bendahara')
            ->where('status', 'aktif');
    }

    public function kegiatan(): HasMany
    {
        return $this->hasMany(Kegiatan::class, 'id_organisasi', 'id');
    }
}