<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function redirectAfterLogin()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'opd' => redirect()->route('opd.dashboard'),
            'kelurahan' => redirect()->route('kelurahan.dashboard'),
            default => redirect('/'),
        };
    }
}
