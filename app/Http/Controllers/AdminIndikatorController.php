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
        Indikator::create([ // Menggunakan model Indikator
            'nama_indikator' => $request->nama_indikator, // Nama kolom: nama_indikator
            'dimensi_label' => $request->dimensi_label, // <-- DITAMBAHKAN
            'opd_id' => $request->opd_id,
        ]);

        return redirect()->route('indikator.index')->with('success', 'Indikator berhasil ditambahkan.'); // Nama rute: indikator.index
    }

    public function update(IndikatorRequest $request, Indikator $indikator) // Parameter model: Indikator $indikator
    {
        $indikator->update([ // Menggunakan model Indikator
            'nama_indikator' => $request->nama_indikator, // Nama kolom: nama_indikator
            'dimensi_label' => $request->dimensi_label, // <-- DITAMBAHKAN
            'opd_id' => $request->opd_id,
        ]);

        return redirect()->route('indikator.index')->with('success', 'Indikator berhasil diperbarui.'); // Nama rute: indikator.index
    }

    public function destroy(Indikator $indikator) // Parameter model: Indikator $indikator
    {
        $indikator->delete();
        return redirect()->route('indikator.index')->with('success', 'Indikator berhasil dihapus.'); // Nama rute: indikator.index
    }
}