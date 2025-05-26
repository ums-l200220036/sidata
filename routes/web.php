<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
});

Route::get('home', function () {
    return view('user/home');
});

Route::get('unggah-data', function () {
    return view('user/unggahdata');
});