<?php

use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\CriterionController;
use App\Http\Controllers\DecisionMakingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/try-data', [DecisionMakingController::class, 'topsis'])->name('try-data');

Route::prefix('criteria')->as('criteria.')->group(function () {
    Route::get('/', [CriterionController::class, 'index'])->name('index');
    Route::post('/', [CriterionController::class, 'store'])->name('store');
    Route::put('/{criterion:id}', [CriterionController::class, 'update'])->name('update');
    Route::delete('/{criterion:id}/delete', [CriterionController::class, 'destroy'])->name('destroy');
    Route::post('/import', [CriterionController::class, 'import'])->name('import');
});

Route::prefix('alternatives')->as('alternatives.')->group(function () {
    Route::get('/', [AlternativeController::class, 'index'])->name('index');
    Route::post('/', [AlternativeController::class, 'store'])->name('store');
    Route::put('/{alternative:id}', [AlternativeController::class, 'update'])->name('update');
    Route::delete('/{alternative:id}/delete', [AlternativeController::class, 'destroy'])->name('destroy');
    Route::post('/import', [AlternativeController::class, 'import'])->name('import');
});
