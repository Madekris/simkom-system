<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisOrganisasi extends Model
{
    protected $guarded = ['id'];

    public function organisasi()
    {
        return $this->hasMany(Organisasi::class, 'id_jenis_organisasi');
    }
}
