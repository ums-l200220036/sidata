<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\DataSektoralImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Indikator;
use Illuminate\Support\Facades\Auth;

class DataSektoralImportController extends Controller
{
    /**
     * Menampilkan form unggah data sektoral.
     */
    public function form()
    {
        $user = Auth::user();

        return view('user.unggahdata', [
            'user' => $user,
            'indikatorList' => Indikator::where('opd_id', $user->opd_id)->get()
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'indikator_id' => 'required|exists:indikator,id'
        ]);

        $user = Auth::user();

        Excel::import(
            new DataSektoralImport($request->indikator_id, $user->opd_id),
            $request->file('file')
        );

        return redirect()->route('dashboard')->with('success', 'Data berhasil diunggah.');
    }
}