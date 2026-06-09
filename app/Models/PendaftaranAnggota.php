<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranAnggota extends Model
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
}
