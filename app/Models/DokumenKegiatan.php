<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenKegiatan extends Model
{
    protected $guarded = ['id'];
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }
}
