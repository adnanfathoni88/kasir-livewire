<?php

use App\Livewire\Category;
use App\Livewire\Produk;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.app');
});


Route::get('kategori', Category::class);
Route::get('produk', Produk::class);
