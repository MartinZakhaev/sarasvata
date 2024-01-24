<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('settings')->group(function () {
        Route::name('settings.')->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });
    });
});

Route::middleware('auth')->group(function () {
    Route::prefix('product')->group(function () {
        Route::name('product.')->group(function () {
            Route::get('/item', [ItemController::class, 'index'])->name('item.index');
            Route::get('/item/{id}', [ItemController::class, 'edit'])->name('item.edit');
            Route::patch('/item/{id}', [ItemController::class, 'update'])->name('item.update');
            Route::delete('/item/{id}', [ItemController::class, 'destroy'])->name('item.destroy');
        });
    });
});

Route::middleware('auth')->group(function () {
    Route::prefix('product')->group(function () {
        Route::name('product.')->group(function () {
            Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
            Route::get('/category/{id}', [CategoryController::class, 'edit'])->name('category.edit');
            Route::patch('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
            Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
        });
    });
});

require __DIR__ . '/auth.php';
