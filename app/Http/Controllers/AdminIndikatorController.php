<?php

namespace App\Http\Controllers;

use App\Models\Indikator; // Import model Indikator
use App\Models\Opd;
use App\Http\Requests\IndikatorRequest; // Import IndikatorRequest

class AdminIndikatorController extends Controller // Nama controller: AdminIndikatorController
{
    public function index()
    {
        $kategoris = Indikator::with('opd')->get(); // Menggunakan Indikator::with('opd')
        $opds = Opd::orderBy('nama_opd')->get();

        return view('admin.kelolakategori', compact('kategoris', 'opds')); // View tetap 'kelolakategori'
    }

    public function store(IndikatorRequest $request)
    {
        // Tidak perlu set route_name atau import_class. Model melakukannya otomatis.
        Indikator::create($request->validated());
        return redirect()->route('indikator.index')->with('success', 'Indikator berhasil ditambahkan.');
    }

    public function update(IndikatorRequest $request, Indikator $indikator)
    {
        $indikator->update($request->validated());
        return redirect()->route('indikator.index')->with('success', 'Indikator berhasil diperbarui.');
    }

    public function destroy(Indikator $indikator) // Parameter model: Indikator $indikator
    {
        $indikator->delete();
        return redirect()->route('indikator.index')->with('success', 'Indikator berhasil dihapus.'); // Nama rute: indikator.index
    }
}