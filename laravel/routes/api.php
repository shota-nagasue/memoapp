<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Memos\ListController;
use App\Http\Controllers\Memos\CreateController;
use App\Http\Controllers\Memos\DeleteController;

Route::get('/memos', ListController::class);
Route::post('/memos', CreateController::class);
Route::delete('/memos/{id}', DeleteController::class);
