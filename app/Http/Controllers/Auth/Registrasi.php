<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class Registrasi extends Controller
{
    public function create()
    {
        return view('pages.auth.register'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => ['required', 'string', 'max:20', 'unique:mahasiswas,nim'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'prodi' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'integer', 'between:1,8'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
        ]);
        

        $user = DB::transaction(function () use ($request) {
            
            $prodi = ProgramStudi::firstOrCreate([
                'nama' => $request->prodi
            ]);

            $user = User::create([
                'email' => $request->nim . '@stikom-bali.ac.id', 
                'no_telepon' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'id_user' => $user->id,
                'nim' => $request->nim,
                'nama' => $request->name,
                'id_program_studi' => $prodi->id,
                'semester' => $request->semester,
            ]);

            return $user;
    });
    
        return redirect()->route('login');
    }
}
