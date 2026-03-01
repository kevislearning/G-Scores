<?php

use App\Http\Controllers\ScoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ScoreController::class, 'index'])->name('home');
Route::post('/api/search', [ScoreController::class, 'search'])->name('api.search');
Route::get('/api/statistics', [ScoreController::class, 'statistics'])->name('api.statistics');
Route::get('/api/top-group-a', [ScoreController::class, 'topGroupA'])->name('api.top-group-a');
