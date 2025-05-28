<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OpdController extends Controller
{
    public function index()
    {
        return view('opd.dashboard');
    }
}
