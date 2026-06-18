<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Pembina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

class Login extends Controller
{
    public function create()
    {
        return view('pages.auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = null;

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();
        if ($mahasiswa) {
            $user = $mahasiswa->user;
        } else {
            $pembina = Pembina::where('nip', $request->nim)->first();
            if ($pembina) {
                $user = $pembina->user;
            } else {
                $user = User::where('email', $request->nim)->first();
            }
        }

        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'nim' => __('auth.failed'),
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $dashboardRoute = match ($user->role) {
            'mahasiswa' => 'mahasiswa.dashboard',
            'admin' => 'admin.dashboard',
            'pengurus' => 'pengurus.dashboard.index',
            'pembina' => 'pembina.dashboard',
            'bendahara' => 'bendahara.dashboard.index',
            default => null,
        };

        if ($dashboardRoute && Route::has($dashboardRoute)) {
            return redirect()->route($dashboardRoute);
        }

        return redirect()->route('mahasiswa.dashboard');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
