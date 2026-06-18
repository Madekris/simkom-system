<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kegiatan extends Model
{
    use LogsActivity;

    // 1. Sesuaikan nama tabel baru yang pakai 's' di belakangnya
    protected $table = 'kegiatans';

    // 2. Aktifkan kembali timestamps karena di SQL baru sudah ada created_at & updated_at
    public $timestamps = true;

    protected $fillable = [
        'id_organisasi',
        'id_periode',
        'judul_kegiatan',
        'deskripsi',
        'tanggal_kegiatan',
        'waktu_kegiatan',
        'lokasi',
        'kuota_peserta',
        'status',
        'evaluasi_kegiatan'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('manajemen_kegiatan') // Dilakukan oleh Pengurus
            ->setDescriptionForEvent(fn(string $eventName) => "Kegiatan '{$this->judul_kegiatan}' telah di {$eventName}");
    }

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeKepengurusan::class, 'id_periode');
    }

    public function dokumenKegiatan()
    {
        return $this->hasMany(DokumenKegiatan::class, 'id_kegiatan');
    }

    public function keuanganKegiatan()
    {
        return $this->hasMany(KeuanganKegiatan::class, 'id_kegiatan');
    }

    public function pendaftaranPesertaKegiatan()
    {
        return $this->hasMany(PendaftaranPesertaKegiatan::class, 'id_kegiatan');
    }
}