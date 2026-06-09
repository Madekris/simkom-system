<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeKepengurusan extends Model
{
    protected $guarded = ['id'];
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
