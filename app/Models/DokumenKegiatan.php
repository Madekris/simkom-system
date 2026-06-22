<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenKegiatan extends Model
{
    protected $table = 'dokumen_kegiatans';

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];
    
    protected $fillable = [
        'id_kegiatan', 
        'jenis_dokumen', 
        'nama_file', 
        'path_url'
    ];

    protected $dates = ['deleted_at'];

    public function kegiatan()
    {
        // Parameter ketiga diubah menjadi 'id' (Primary Key tabel kegiatans)
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan', 'id');
    }
}