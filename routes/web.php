<?php

use App\Livewire\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.app');
});


Route::get('kategori', Category::class);
