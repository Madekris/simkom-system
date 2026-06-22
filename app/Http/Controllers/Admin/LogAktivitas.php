<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class LogAktivitas extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer')->latest();

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $logs     = $query->paginate(25)->withQueryString();
        $logNames = Activity::distinct()->orderBy('log_name')->pluck('log_name');

        $totalHariIni  = Activity::whereDate('created_at', today())->count();
        $total7Hari    = Activity::where('created_at', '>=', now()->subDays(7))->count();
        $totalSemua    = Activity::count();

        return view('pages.admin.log-aktivitas', compact(
            'logs', 'logNames', 'totalHariIni', 'total7Hari', 'totalSemua'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = Activity::with('causer')->latest();

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $logs = $query->get();

        $pdf = Pdf::loadView('pages.cetak.log-aktivitas-pdf', [
            'logs'     => $logs,
            'namaUser' => Auth::user()->name,
            'role'     => 'Admin',
            'judul'    => 'Log Aktivitas Sistem — Semua Pengguna',
            'isAdmin'  => true,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('log-aktivitas-sistem-' . now()->format('Y-m-d') . '.pdf');
    }
}
