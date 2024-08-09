<?php

use App\Http\Controllers\OrderController;
use App\Livewire\Category;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Order;
use App\Livewire\Product;
use Illuminate\Support\Facades\Route;

// admin
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/kategori', Category::class);
    Route::get('/produk', Product::class);
});

// user
Route::get('/', Product::class)->middleware('auth');
Route::get('/login', Login::class)->name('login');
Route::get('/logout', [Login::class, 'logout']);
Route::get('/register', Register::class);

// status
Route::get('')

Route::post('/snap-token', [Order::class, 'createSnapToken'])->name('snap.token')->middleware('auth');
