<?php
namespace App\Http\Controllers;

use App\Models\WeatherLog;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index()
{
    // 1. 全データを日付順に取得
    $logs = \App\Models\WeatherLog::orderBy('date', 'asc')->get();

    // 2. 積算温度（スギ用：1月1日〜）を計算
    $totalTemp = $logs->sum('max_temp');

    // 3. 積算温度（ブタクサ用：8月1日〜）を計算
    // ※ 2026年8月はまだ先なので、今は 0 になるはずです
    $ragweedTemp = \App\Models\WeatherLog::where('date', '>=', date('Y-08-01'))->sum('max_temp');

    // 4. ビュー（画面）にこれらを全部渡す
    return view('weather.index', compact('logs', 'totalTemp', 'ragweedTemp'));
}
}