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

    /**
     * RELASI A: Langsung mendapatkan daftar KEGIATAN yang diikuti (Many-to-Many)
     * Direkomendasikan untuk halaman "Kegiatan Saya" Anda saat ini.
     */
    public function pendaftaranKegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'pendaftaran_peserta_kegiatans', 'id_user', 'id_kegiatan')
                    ->withPivot('status') // Mengambil status pendaftaran mahasiswa (jika ada)
                    ->withTimestamps();
    }

    /**
     * RELASI B: Mendapatkan data baris log pendaftarannya saja (One-to-Many)
     * Digunakan jika Anda ingin memanipulasi atau melihat data mentah dari tabel pivot.
     */
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
