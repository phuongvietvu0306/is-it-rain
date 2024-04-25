<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;


// Weather route group
Route::get('/', [WeatherController::class, 'index']);
