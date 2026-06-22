<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DokumenKegiatan extends Model
{

    protected $table = 'dokumen_kegiatans';
    
    protected $fillable = [
        'id_kegiatan', 
        'jenis_dokumen', 
        'nama_file', 
        'path_url'
    ];

    // Daftarkan kolom deleted_at agar dikenali sebagai komponen tanggal oleh Laravel
    protected $dates = ['deleted_at'];

    public function kegiatan()
    {
        // Parameter: NamaModel, Foreign_Key_Tabel_Ini, Local_Key_Tabel_Target
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan', 'id_kegiatan');
    }
}