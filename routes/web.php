<?php

use Illuminate\Support\Facades\Route;

Route::get('/game', function () {
    return view('game');
});

Route::get('/sandbox', function () {
    return view('sandbox');
});
