<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Indikator; // Don't forget to import your Indikator model!

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;
        $indikators = collect(); // Initialize an empty collection

        // Only fetch indicators if the role is 'opd'
        if ($role === 'opd') {
            $userOpdId = Auth::user()->opd_id; // Get the opd_id from the authenticated user

            // Fetch indicators where the opd_id matches the user's opd_id
            $indikators = Indikator::where('opd_id', $userOpdId)->get();

            // Optional: If you only need specific columns, chain select():
            // $indikators = Indikator::where('opd_id', $userOpdId)->select('nama_indikator')->get();
        }

        return view('dashboard', compact('role', 'indikators'));
    }
}