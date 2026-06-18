<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AnggotaOrganisasi extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('anggota_organisasi')
            ->setDescriptionForEvent(fn(string $eventName) => "Data Anggota Organisasi telah di {$eventName}");
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeKepengurusan::class, 'id_periode');
    }

    public function mahasiswa()
    {
        // Sesuaikan foreign key dengan database Anda (id_user)
        return $this->belongsTo(Mahasiswa::class, 'id_user', 'id_user');
    }
}