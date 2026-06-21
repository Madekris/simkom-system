<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
use App\Models\Organisasi;
use App\Models\PeriodeKepengurusan;
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
            $dPengguna = User::with('mahasiswa.programStudi', 'pembina', 'anggotaOrganisasi.organisasi')
            ->whereIn('role', ['pengurus', 'pembina', 'bendahara', 'admin'])
            ->where('id', $edit) // Mengecualikan user yang sedang login
            ->first();

            $org = Organisasi::get();
            // dd($dPengguna->toArray());
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
            'status'            => 'required|in:aktif,nonaktif',
            'jabatan'           => 'required_if:role,pengurus|nullable|string|max:255',
            'email'             => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id), 
            ],
            'id_program_studi'  => 'required_unless:role,pembina,admin',
            'semester'          => 'required_unless:role,pembina,admin',
            'id_organisasi'     => 'nullable',
        ], [
            // Pesan Error untuk Nama
            'nama.required'             => 'Nama lengkap wajib diisi.',
            'nama.string'               => 'Nama lengkap harus berupa teks.',
            'nama.max'                  => 'Nama lengkap tidak boleh lebih dari 255 karakter.',

            // Pesan Error untuk Role & Status
            'role.required'             => 'Silakan pilih role pengguna.',
            'role.in'                   => 'Role yang dipilih tidak valid.',
            'status.required'           => 'Silakan pilih status akun.',
            'status.in'                 => 'Status yang dipilih tidak valid.',

            // Pesan Error untuk Jabatan
            'jabatan.required_if'       => 'Jabatan wajib diisi jika Anda memilih role Pengurus.',
            'jabatan.max'               => 'Nama jabatan tidak boleh lebih dari 255 karakter.',

            // Pesan Error untuk Email
            'email.required'            => 'Alamat email wajib diisi.',
            'email.email'               => 'Format alamat email tidak valid.',
            'email.unique'              => 'Email ini sudah digunakan oleh pengguna lain.',

            // Pesan Error untuk Akademik
            'id_program_studi.required' => 'Silakan pilih program studi.',
            'semester.required'         => 'Semester wajib diisi.',
        ]);

        // 3. Proses Update ke Database
        // Update data di tabel Users

        // dd($validated['id_organisasi']);


       if ($validated['status']) {
            // Menggunakan first() untuk mengambil satu data saja
            $aO = AnggotaOrganisasi::where('id_organisasi', $validated['id_organisasi'])
                                    ->where('id_user', $id)
                                    ->first();
            // dd($aO->toArray());

            if($validated['role'] == 'pembina'){
                $aOp = AnggotaOrganisasi::whereIn('id_organisasi', $validated['id_organisasi'])
                    ->where('id_user', $id)
                    ->get();

                $userAnggota = AnggotaOrganisasi::where('id_user', $id)->pluck('id_organisasi')->toArray();

                $organisasiBaru = $validated['id_organisasi'] ?? [];

                // 3. ELIMINASI: Cari ID yang ada di database LAMA, tapi TIDAK ADA di form BARU (Artinya dihapus oleh user)
                $organisasiYangDihapus = array_diff($userAnggota, $organisasiBaru);

                // 4. Eksekusi penghapusan di database untuk organisasi yang dieliminasi
                if (!empty($organisasiYangDihapus)) {
                    AnggotaOrganisasi::where('id_user', $id)
                        ->whereIn('id_organisasi', $organisasiYangDihapus)
                        ->delete();
                }

                $validated['jabatan'] = 'Pembina';
                foreach ($validated['id_organisasi'] as $idOrg) {
                    foreach ($aOp as $oP) {
                        
                        $isPembina = $idOrg == $oP->id;

                        if(!$isPembina){
                            $periode = PeriodeKepengurusan::where([
                                ['id_organisasi', '=', $idOrg],
                                ['status_periode', '=', 'aktif']
                            ])->first(); // Jangan lupa tambahkan ->first() untuk mengambil datanya

                            AnggotaOrganisasi::updateOrCreate(
                                // 1. Kriteria Unik untuk Pengecekan (Kombinasi id_user DAN id_organisasi)
                                [
                                    'id_user'       => $id,
                                    'id_organisasi' => $idOrg,
                                ],
                                // 2. Data yang akan di-INSERT (jika baru) atau di-UPDATE (jika sudah ada)
                                [
                                    'id_periode'    => $periode->id,
                                    'jabatan'       => 'Pembina',
                                    'status'        => $validated['status'],
                                ]
                            );
                        }

                        // dd($oP->id);
                    }
                }
                $aO->update([
                    'status' => $validated['status'],
                ]);
                // // dd($aO->toArray());
            }

            $isExist = [];
            if($validated['role'] == 'pengurus') {
                $isExist = AnggotaOrganisasi::where('id_organisasi', $validated['id_organisasi'])
                    ->where('jabatan', $validated['jabatan'])
                    ->where('id_user', '!=', $id) 
                    ->first();

                // dd($isExist->toArray());
            } elseif ($validated['role'] == 'bendahara')
            {
                $isExist = AnggotaOrganisasi::where('id_organisasi', $validated['id_organisasi'])
                    ->where('jabatan', 'Bendahara')
                    ->where('id_user', '!=', $id) 
                    ->first();
            }

            // Pastikan datanya ditemukan sebelum di-update untuk menghindari error null
            if ($aO) {

                $jbtn = $validated['jabatan'];
                if ($validated['role'] == 'bendahara'){
                    $jbtn = 'Bendahara';

                } elseif ($validated['role'] == 'mahasiswa') {
                    $jbtn = 'Anggota';
                }

                if(!$isExist) {
                    $aO->update([
                        'status' => $validated['status'],
                        'jabatan' => $jbtn
                    ]);

                } else {
                    return back()
                        ->withInput() // Agar data yang sudah diisi di form tidak hilang/ter-reset
                        ->withErrors([
                            'jabatan' => "Jabatan '{$jbtn}' di organisasi ini sudah ada yang menempati."
                        ]);
                }

            }
             $user->update([
                'email' => $validated['email'],
                'role'  => $validated['role'],
            ]);


            
            // dd($aO->toArray()); // Sekarang dd() ini akan berfungsi
        }

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
        // if ($request->has('id_organisasi')) {
        //     $user->anggotaOrganisasi()->updateOrCreate(
        //         ['id_user' => $user->id], // keyword pencarian
        //         [
        //             'id_organisasi' => $validated['id_organisasi'],
        //             'status'        => $validated['status']
        //         ]
        //     );
        // }

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
