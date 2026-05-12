<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TugasController;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [TugasController::class, 'dashboard'])->name('dashboard');

    Route::get('/tugas/create', [TugasController::class, 'create']);
    Route::post('/tugas/store', [TugasController::class, 'store']);
    Route::get('/tugas/{id}', [TugasController::class, 'show']);
    Route::get('/tugas/{id}/edit', [TugasController::class, 'edit']);
    Route::post('/tugas/{id}/update', [TugasController::class, 'update']);
    Route::post('/tugas/{id}/selesai', [TugasController::class, 'selesai']);
    Route::post('/tugas/{id}/delete', [TugasController::class, 'destroy']);

    Route::get('/categories', [KategoriController::class, 'index']);
    Route::post('/categories/store', [KategoriController::class, 'store']);

    Route::get('/profile', [ProfileController::class, 'index']);
    Route::get('/profile/edit', [ProfileController::class, 'edit']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
});