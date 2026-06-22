<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class LogAktivitas extends Controller
{
    public function index()
    {
        $logs = Activity::where('causer_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('pages.pembina.log-aktivitas', compact('logs'));
    }

    public function exportPdf()
    {
        $logs = Activity::where('causer_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        $pdf = Pdf::loadView('pages.cetak.log-aktivitas-pdf', [
            'logs'     => $logs,
            'namaUser' => Auth::user()->name,
            'role'     => 'Pembina',
            'judul'    => 'Log Aktivitas Pembina',
            'isAdmin'  => false,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('log-aktivitas-pembina-' . now()->format('Y-m-d') . '.pdf');
    }
}
