<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Wilayah;
use App\Models\OPD;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::with('wilayah')->get();
        $wilayahs = Wilayah::all();

        // Ambil kecamatan saja, yaitu yang parent_id = NULL
        $kecamatans = Wilayah::whereNull('parent_id')->get();

        $opds = OPD::all();

        return view('admin.kelolapengguna', compact('users', 'wilayahs', 'opds', 'kecamatans'));
    }

    public function store(UserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'opd_id' => $request->opd_id,
            'wilayah_id' => $request->wilayah_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'opd_id' => $request->opd_id,
            'wilayah_id' => $request->wilayah_id,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }
}