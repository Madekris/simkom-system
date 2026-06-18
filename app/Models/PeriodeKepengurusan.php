<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PeriodeKepengurusan extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('periode_kepengurusan')
            ->setDescriptionForEvent(fn(string $eventName) => "Periode Kepengurusan telah di {$eventName}");
    }

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }

    public function anggotaOrganisasi()
    {
        return $this->hasMany(AnggotaOrganisasi::class, 'id_periode');
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'id_periode');
    }
}