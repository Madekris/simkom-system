<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class JenisOrganisasi extends Model
{
    use HasFactory, LogsActivity;

    // Mengunci nama tabel sesuai phpMyAdmin kamu
    protected $table = 'jenis_organisasis';

    protected $fillable = [
        'nama_jenis', 
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('jenis_organisasi')
            ->setDescriptionForEvent(fn(string $eventName) => "Jenis Organisasi telah di {$eventName}");
    }

    /**
     * Relasi ke model Organisasi (Satu jenis bisa memiliki banyak organisasi)
     */
    public function organisasis(): HasMany
    {
        return $this->hasMany(Organisasi::class, 'id_jenis_organisasi');
    }
}