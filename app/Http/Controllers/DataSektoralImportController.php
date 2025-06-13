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
        $indikator = Indikator::find($request->indikator_id);

        // 1. Dapatkan nama Class Parser dari database
        $importClassName = $indikator->import_class;

        // 2. Periksa apakah nama Class valid dan filenya ada
        if (empty($importClassName) || !class_exists("App\\Imports\\{$importClassName}")) {
            return redirect()->back()->withErrors(['file' => 'Tipe parser import untuk indikator ini belum dikonfigurasi.']);
        }
        
        // 3. Buat objek Import secara dinamis
        $importerClass = "App\\Imports\\{$importClassName}";
        $importerObject = new $importerClass($request->indikator_id, $user->opd_id);

        // 4. Jalankan import dengan parser yang benar
        Excel::import($importerObject, $request->file('file'));

        return redirect()->route('dashboard')->with('success', 'Data berhasil diunggah.');
    }
}