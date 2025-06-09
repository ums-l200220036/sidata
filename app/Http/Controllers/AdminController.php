<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Wilayah;
use App\Models\Opd; // Pastikan menggunakan Opd, bukan OPD
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::with(['wilayah.parent', 'opd'])->get(); // Load relasi parent untuk wilayah
        $kecamatans = Wilayah::whereNull('kelurahan')->get(); // Wilayah yang adalah kecamatan
        $kelurahans = Wilayah::whereNotNull('kelurahan')->get(); // Wilayah yang adalah kelurahan
        // Ambil semua OPD, lalu filter di frontend untuk memisahkan kecamatan jika diperlukan
        $allOpds = Opd::all();

        return view('admin.kelolapengguna', compact('users', 'kecamatans', 'kelurahans', 'allOpds'));
    }

    public function store(UserRequest $request)
    {
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ];

        $userData['opd_id'] = null; // Default null
        $userData['wilayah_id'] = null; // Default null

        if ($request->role === 'opd') {
            $userData['opd_id'] = $request->opd_id;
        } elseif ($request->role === 'kecamatan') {
            // Untuk role 'kecamatan', opd_id dan wilayah_id adalah sama (ID kecamatan)
            // Cari ID OPD yang namanya sesuai dengan nama kecamatan yang dipilih
            $kecamatanOpd = Opd::where('nama_opd', Wilayah::find($request->wilayah_id)->kecamatan)->first();
            if ($kecamatanOpd) {
                $userData['opd_id'] = $kecamatanOpd->id;
            }
            $userData['wilayah_id'] = $request->wilayah_id;
        } elseif ($request->role === 'kelurahan') {
            $userData['wilayah_id'] = $request->wilayah_id;
        }

        User::create($userData);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(UserRequest $request, User $user)
    {
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $userData['opd_id'] = null;
        $userData['wilayah_id'] = null;

        if ($request->role === 'opd') {
            $userData['opd_id'] = $request->opd_id;
        } elseif ($request->role === 'kecamatan') {
            $kecamatanOpd = Opd::where('nama_opd', Wilayah::find($request->wilayah_id)->kecamatan)->first();
            if ($kecamatanOpd) {
                $userData['opd_id'] = $kecamatanOpd->id;
            }
            $userData['wilayah_id'] = $request->wilayah_id;
        } elseif ($request->role === 'kelurahan') {
            $userData['wilayah_id'] = $request->wilayah_id;
        }

        $user->update($userData);

        return redirect()->back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }
}