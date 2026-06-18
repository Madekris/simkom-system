<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

#[Fillable(['name', 'email', 'password', 'no_telepon', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('akun_user')
            ->setDescriptionForEvent(fn(string $eventName) => "Akun User '{$this->email}' telah di {$eventName}");
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id_user');
    }

    public function pembina()
    {
        return $this->hasOne(Pembina::class, 'id_user');
    }

    public function anggotaOrganisasi()
    {
        return $this->hasMany(AnggotaOrganisasi::class, 'id_user');
    }

    public function pendaftaranAnggota()
    {
        return $this->hasMany(PendaftaranAnggota::class, 'id_user');
    }

    public function pendaftaranKegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'pendaftaran_peserta_kegiatans', 'id_user', 'id_kegiatan')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function logPendaftaran()
    {
        return $this->hasMany(PendaftaranPesertaKegiatan::class, 'id_user');
    }

    public function getNameAttribute(): string
    {
        return $this->mahasiswa?->nama
            ?? $this->pembina?->nama
            ?? $this->email;
    }

    public function getNimAttribute(): ?string
    {
        return $this->mahasiswa?->nim;
    }

    public function getNipAttribute(): ?string
    {
        return $this->pembina?->nip;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}