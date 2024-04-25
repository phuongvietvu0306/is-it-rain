<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WeatherController extends Controller
{
    public function __construct() {
        $this->open_weather_map_key = env('OPEN_WEATHER_MAP_KEY', '');
        $this->geo_db_key = env('GEO_DB_KEY', '');
    }

    public function index(Request $request)
    {
        $props = [
            'latitude'  => null,
            'longitude' => null,
            'label'     => __('Current location'),
        ];

        // Get user IP address
        $ip = $request->getClientIp();

        // Get user IP address for vercel
        $ip = $request->header('x-forwarded-for');

        $response = Http::get("http://ip-api.com/json/{$ip}");
        if ($response->successful()) {
            $data = $response->object();
            if ($data->status !== 'success') {
                goto render;
            }

            $props['latitude'] = $data->lat;
            $props['longitude'] = $data->lon;
            $props['label'] = "{$data->city}, {$data->country}";
        }

        render:
            return Inertia::render('Weather', $props);
    }

    public function getWeather(Request $request)
    {
        return $this->queryOpenWeatherMap('weather', $request->get('lat'), $request->get('lon'));
    }

    public function getForecast(Request $request)
    {
        return $this->queryOpenWeatherMap('forecast', $request->get('lat'), $request->get('lon'));
    }

    public function getCities(Request $request)
    {
        $location = $request->get('location');

        $url = "https://wft-geo-db.p.rapidapi.com/v1/geo/cities?minPopulation=10000&namePrefix={$location}";

        $response = Http::withHeaders([
            'X-RapidAPI-Key' => $this->geo_db_key,
            'X-RapidAPI-Host' => 'wft-geo-db.p.rapidapi.com'
        ])->get($url);

        if (! $response->successful()) {
            return response()->json(['message' => 'Could not get cities'], 422);
        }

        return $response->json();
    }

    protected function queryOpenWeatherMap($feat, $lat, $lon)
    {
        $latitude = $lat;
        $longitude = $lon;

        $url = "https://api.openweathermap.org/data/2.5/{$feat}?lat={$latitude}&lon={$longitude}&appid={$this->open_weather_map_key}&units=metric";

        $response = Http::get($url);
        if (! $response->successful()) {
            return response()->json(['message' => 'Could not get forecast'], 422);
        }

        return $response->json();
    }
}
