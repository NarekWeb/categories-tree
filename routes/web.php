<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('categories');
});

Route::get('/categories',[App\Http\Controllers\CategoryController::class, 'index'])->name('categories');
Route::get('/categories/create',[App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories',[App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{category}',[App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{category}',[App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
