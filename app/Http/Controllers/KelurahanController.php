<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KelurahanController extends Controller
{
    public function index()
    {
        return view('kelurahan.dashboard');
    }
}
