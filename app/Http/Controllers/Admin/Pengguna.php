<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Pengguna extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $userId = Auth::id();

       $id = $request->id;

        $pengguna = User::with('mahasiswa', 'pembina', 'anggotaOrganisasi')
            ->whereIn('role', ['pengurus', 'pembina', 'bendahara', 'admin'])
            ->where('id', '!=', $userId) // Mengecualikan user yang sedang login
            ->get();


        $dPengguna = [];

        if($id){
            $dPengguna = User::with('mahasiswa.programStudi', 'pembina', 'anggotaOrganisasi')
            ->whereIn('role', ['pengurus', 'pembina', 'bendahara', 'admin'])
            ->where('id', $id) // Mengecualikan user yang sedang login
            ->first();
        }
        // dd($dPengguna->toArray());
        return view('pages.admin.pengguna', compact('pengguna', 'dPengguna'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
