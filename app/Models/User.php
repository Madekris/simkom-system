<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'no_telepon', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id_user');
    }

    // Relasi ke profil pembina
    public function pembina()
    {
        return $this->hasOne(Pembina::class, 'id_user');
    }

    // Relasi ke riwayat keanggotaan organisasi
    public function anggotaOrganisasi()
    {
        return $this->hasMany(AnggotaOrganisasi::class, 'id_user');
    }

    // Relasi ke pengajuan pendaftaran anggota baru
    public function pendaftaranAnggota()
    {
        return $this->hasMany(PendaftaranAnggota::class, 'id_user');
    }

    // Relasi ke pendaftaran peserta kegiatan
    public function pendaftaranPesertaKegiatan()
    {
        return $this->hasMany(PendaftaranPesertaKegiatan::class, 'id_user');
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
