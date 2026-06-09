<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organisasi extends Model
{
    protected $guarded = ['id'];

    public function jenisOrganisasi()
    {
        return $this->belongsTo(JenisOrganisasi::class, 'id_jenis_organisasi');
    }

    public function periodeKepengurusan()
    {
        return $this->hasMany(PeriodeKepengurusan::class, 'id_organisasi');
    }

    public function anggotaOrganisasi()
    {
        return $this->hasMany(AnggotaOrganisasi::class, 'id_organisasi');
    }

    public function pendaftaranAnggota()
    {
        return $this->hasMany(PendaftaranAnggota::class, 'id_organisasi');
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'id_organisasi');
    }
}
