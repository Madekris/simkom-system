<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaOrganisasi extends Model
{
    protected $guarded = ['id'];

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
