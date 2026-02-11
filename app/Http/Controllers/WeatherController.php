<?php
namespace App\Http\Controllers;

use App\Models\WeatherLog;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index()
{
    // 全データを日付順に取得
    $logs = \App\Models\WeatherLog::orderBy('date', 'asc')->get();
    
    // 合計値を計算
    $totalTemp = $logs->sum('max_temp');

    return view('weather.index', compact('logs', 'totalTemp'));
}
}