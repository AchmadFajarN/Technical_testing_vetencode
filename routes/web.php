<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\distributionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/distributions', [DistributionController::class, 'index'])->name('distributions.index');
Route::get('/distributions/create', [DistributionController::class, 'create'])->name('distributions.create');








