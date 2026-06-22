<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PendaftaranPesertaKegiatan extends Model
{

    // Definisikan nama tabel secara eksplisit jika tidak menggunakan default singular-plural otomatis
    protected $table = 'pendaftaran_peserta_kegiatans';

    protected $fillable = [
        'id_kegiatan',
        'id_user',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('pendaftaran_kegiatan')
            ->setDescriptionForEvent(fn(string $eventName) => "Pendaftaran Peserta Kegiatan telah di {$eventName}");
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}