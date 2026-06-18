<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PendaftaranAnggota extends Model
{
    use LogsActivity;

    protected $fillable = [
        'id_user',
        'id_organisasi',
        'status',
        'keterangan'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('verifikasi_anggota') // Mahasiswa mendaftar, Pengurus verifikasi
            ->setDescriptionForEvent(fn(string $eventName) => "Status Pendaftaran Anggota telah di {$eventName}");
    }

    public function organisasi(): BelongsTo
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}