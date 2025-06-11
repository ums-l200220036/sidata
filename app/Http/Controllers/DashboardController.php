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
        $indikators = collect(); // Initialize as an empty collection

        // Fetch ALL indicators if the user is authenticated.
        // No role-specific filtering for which indicators are shown on the dashboard itself.
        if (Auth::check()) {
            $indikators = Indikator::all();
            // If you only need specific columns, you can select them:
            // $indikators = Indikator::select('id', 'nama_indikator')->get();
        }

        // Pass all relevant data to the view
        return view('dashboard', compact('role', 'indikators'));
    }
}