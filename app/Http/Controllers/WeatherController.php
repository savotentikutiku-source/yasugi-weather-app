<?php
namespace App\Http\Controllers;

use App\Models\WeatherLog;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index()
{
    $logs = \App\Models\WeatherLog::orderBy('date', 'asc')->get();
    
    // スギ用：1月1日からの合計（現在のコード）
    $totalTemp = \App\Models\WeatherLog::where('date', '>=', date('Y-01-01'))->sum('max_temp');

    // ★追加：ブタクサ用（8月1日からの合計）
    $ragweedTemp = \App\Models\WeatherLog::where('date', '>=', date('Y-08-01'))->sum('max_temp');

    return view('weather.index', compact('logs', 'totalTemp', 'ragweedTemp'));
}
}