<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;


Route::get('weather', [WeatherController::class, 'getWeather']);
Route::get('forecast', [WeatherController::class, 'getForecast']);
Route::get('cities', [WeatherController::class, 'getCities']);
