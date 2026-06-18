<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DaftarKegiatan extends Controller
{
    public function index ()
    {
        return view('pages.mahasiswa.daftar-kegiatan');
    }
    public function show (string $id)
    {

        return view('pages.mahasiswa.daftar-kegiatan-detail');
    }
}
