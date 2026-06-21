<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranPesertaKegiatan extends Model
{

    // Definisikan nama tabel secara eksplisit jika tidak menggunakan default singular-plural otomatis
    protected $table = 'pendaftaran_peserta_kegiatans';

    protected $fillable = [
        'id_kegiatan',
        'id_user',
        'status',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
