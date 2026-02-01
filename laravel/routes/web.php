<?php

use Illuminate\Support\Facades\Route;

/**
 * SPAのルーティングを全て受け取る
 */
Route::view('/{any?}', 'app')->where('any', '.*');
