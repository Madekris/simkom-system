<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranPesertaKegiatan extends Model
{
    protected $guarded = ['id'];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
