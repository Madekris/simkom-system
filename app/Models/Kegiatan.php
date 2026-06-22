<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kegiatan extends Model
{
    use LogsActivity;

    protected $table = 'kegiatans';

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
        'evaluasi_kegiatan',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('manajemen_kegiatan') // Dilakukan oleh Pengurus
            ->setDescriptionForEvent(function (string $eventName) {
                return match ($eventName) {
                    'created' => "Kegiatan '{$this->judul_kegiatan}' berhasil dibuat dan menunggu persetujuan",
                    'updated' => "Kegiatan '{$this->judul_kegiatan}' berhasil diperbarui",
                    'deleted' => "Kegiatan '{$this->judul_kegiatan}' telah dihapus",
                    default   => "Kegiatan '{$this->judul_kegiatan}' mengalami perubahan: {$eventName}",
                };
            });
    }

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeKepengurusan::class, 'id_periode');
    }

    // Nama jamak 'dokumenKegiatans' dipakai oleh AdminController
    public function dokumenKegiatans()
    {
        return $this->hasMany(DokumenKegiatan::class, 'id_kegiatan', 'id');
    }

    // Alias tunggal untuk pemanggilan lama
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
