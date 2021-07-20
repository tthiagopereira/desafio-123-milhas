<?php

use App\Http\Controllers\VoosController;
use Illuminate\Support\Facades\Route;

Route::get('voos', [VoosController::class, 'index']);
Route::get('voos-group-going', [VoosController::class, 'groupgoing']);
Route::get('voos-group-return', [VoosController::class, 'groupReturn']);
Route::get('voos-fare', [VoosController::class, 'fare']);
