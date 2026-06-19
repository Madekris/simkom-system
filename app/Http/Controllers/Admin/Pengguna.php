<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Pengguna extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $userId = Auth::id();

       $id = $request->id;
       $edit = $request->edit;

        $pengguna = User::with('mahasiswa', 'pembina', 'anggotaOrganisasi')
            ->whereIn('role', ['pengurus', 'pembina', 'bendahara', 'admin'])
            ->where('id', '!=', $userId) // Mengecualikan user yang sedang login
            ->get();


        $dPengguna = [];
        $org = [];

        if($id){
            $dPengguna = User::with('mahasiswa.programStudi', 'pembina', 'anggotaOrganisasi')
            ->whereIn('role', ['pengurus', 'pembina', 'bendahara', 'admin'])
            ->where('id', $id) // Mengecualikan user yang sedang login
            ->first();
        }
        if($edit){
            $dPengguna = User::with('mahasiswa.programStudi', 'pembina', 'anggotaOrganisasi')
            ->whereIn('role', ['pengurus', 'pembina', 'bendahara', 'admin'])
            ->where('id', $edit) // Mengecualikan user yang sedang login
            ->first();

            $org = Organisasi::get();
        }

        $ps = ProgramStudi::get();

        // dd($org->toArray());
        // dd($dPengguna->toArray());
        return view('pages.admin.pengguna', compact(
            'pengguna',
            'dPengguna',
            'org',
            'ps'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::with('mahasiswa', 'pembina')->findOrFail($id);

        // 2. Jalankan Validasi Data
        $validated = $request->validate([
            'nama'              => 'required|string|max:255',
            'role'              => 'required|in:admin,pembina,pengurus,bendahara,mahasiswa',
            'status'            => 'required|in:aktif,tidak aktif',
            'email'             => [
                'required',
                'email',
                // Mengizinkan email ini tetap sama untuk user ini, tapi harus unik dari user lain
                Rule::unique('users', 'email')->ignore($user->id), 
            ],
            // Validasi kondisional: Hanya wajib diisi jika user memiliki relasi/role mahasiswa
            'id_program_studi'  => 'required',
            'semester'          => 'required',
            'id_organisasi'     => 'nullable',
        ], [
            // Kustomisasi Pesan Error jika diperlukan (Opsional)
            'nama.required'    => 'Nama lengkap wajib diisi.',
            'email.unique'     => 'Email ini sudah digunakan oleh pengguna lain.',
            'semester.between' => 'Semester harus berada di antara rentang 1 sampai 8.',
        ]);


        // 3. Proses Update ke Database
        // Update data di tabel Users
        $user->update([
            'email' => $validated['email'],
            'role'  => $validated['role'],
        ]);

        // Update data di tabel Mahasiswa (jika user tersebut adalah mahasiswa/pengurus)
        if ($user->mahasiswa) {
            $user->mahasiswa->update([
                'nama'             => $validated['nama'],
                'id_program_studi' => $validated['id_program_studi'],
                'semester'         => $validated['semester'],
            ]);
        } else if ($user->pembina) {
            // Jika ada relasi pembina, update nama di tabel pembinanya
            $user->pembina->update([
                'nama' => $validated['nama'],
            ]);
        }

        // Update status atau organisasi di tabel anggota_organisasi (jika ada)
        if ($request->has('id_organisasi')) {
            $user->anggotaOrganisasi()->updateOrCreate(
                ['id_user' => $user->id], // keyword pencarian
                [
                    'id_organisasi' => $validated['id_organisasi'],
                    'status'        => $validated['status']
                ]
            );
        }

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->route('admin.pengguna.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
