<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProgramStudi extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('program_studi')
            ->setDescriptionForEvent(fn(string $eventName) => "Program Studi telah di {$eventName}");
    }

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_program_studi');
    }
}