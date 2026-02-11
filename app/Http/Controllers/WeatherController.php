<?php
namespace App\Http\Controllers;

use App\Models\WeatherLog;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index() {
        $totalTemp = WeatherLog::sum('max_temp'); // 累積を計算
        return view('weather', ['total' => $totalTemp]); // 画面（weather.blade.php）に渡す
    }
}