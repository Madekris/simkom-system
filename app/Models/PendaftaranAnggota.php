<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendaftaranAnggota extends Model
{
    protected $fillable = [
        'id_user',
        'id_organisasi',
        'status',
        'keterangan'
    ];

    public function organisasi(): BelongsTo
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}