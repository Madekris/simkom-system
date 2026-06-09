<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $guarded = ['id'];
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_program_studi');
    }
}
