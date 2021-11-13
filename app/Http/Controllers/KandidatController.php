<?php

namespace App\Http\Controllers;

use App\Models\Kandidat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KandidatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Master Data"], ['name' => "Data Kandidat"]
        ];
        return view('content.kandidat.index', compact('breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Master Data"], ['name' => "Data Kandidat"], ['name' => "Tambah Data"]
        ];
        return view('content.kandidat.create', compact('breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required'],
            'angkatan' => ['required'],
            'nim' => ['required'],
            'fakultas' => ['required'],
            'prodi' => ['required'],
            'deskripsi' => ['required'],
            'visi' => ['required'],
            'misi' => ['required'],
            'foto' => ['required', 'image'],
        ]);
        // NOTE: Disimpen filenya ke folder public biar lebih gampang pas di hosting yang gabisa symlink
        $fileName = $request->nama . '-' . time() . '.' . $request->file('foto')->extension();
        $request->file('foto')->move(public_path('kandidat'), $fileName);
        Kandidat::create([
            'nama' => $request->nama,
            'angkatan' => $request->angkatan,
            'nim' => $request->nim,
            'fakultas' => $request->fakultas,
            'prodi' => $request->prodi,
            'deskripsi' => $request->deskripsi,
            'visi' => $request->visi,
            'misi' => $request->misi,
            'foto' => 'kandidat/' . $fileName,
        ]);
        return redirect()->route('kandidat.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kandidat  $kandidat
     * @return \Illuminate\Http\Response
     */
    public function show(Kandidat $kandidat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kandidat  $kandidat
     * @return \Illuminate\Http\Response
     */
    public function edit(Kandidat $kandidat)
    {
        return view('content.kandidat.edit', compact('kandidat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kandidat  $kandidat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kandidat $kandidat)
    {
        // NOTE: Disimpen filenya ke folder public biar lebih gampang pas di hosting yang gabisa symlink
        if ($request->hasFile('foto')) {
            unlink($kandidat->foto);
            $fileName = $request->nama . '-' . time() . '.' . $request->file('foto')->extension();
            $request->file('foto')->move(public_path('kandidat'), $fileName);
            $kandidat->foto = 'kandidat/' . $fileName;
            $kandidat->save();
        }
        // $kandidat->update($request->all());
        return redirect()->route('kandidat.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kandidat  $kandidat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kandidat $kandidat)
    {
        $kandidat->delete();
        return redirect()->route('kandidat.index');
    }
}