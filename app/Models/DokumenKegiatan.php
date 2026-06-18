<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenKegiatan extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'id_kegiatan',
        'jenis_dokumen',
        'nama_file',
        'path_url',
    ];
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }
}
