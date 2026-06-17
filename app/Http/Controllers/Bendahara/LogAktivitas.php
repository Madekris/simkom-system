<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogAktivitas extends Controller
{
    public function index () {
        return view('pages.bendahara.log-aktivitas');
    }
}
