<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Mahasiswa extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('profil_mahasiswa')
            ->setDescriptionForEvent(fn(string $eventName) => "Profil Mahasiswa '{$this->nama}' telah di {$eventName}");
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_program_studi');
    }
}