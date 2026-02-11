<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\WeatherLog;
use Carbon\Carbon;

class FetchDailyWeather extends Command
{
    // ターミナルで実行する時の名前
    protected $signature = 'weather:fetch';
    protected $description = '安来市の昨日の最高気温を取得して保存します';

    public function handle()
    {
        // 昨日の日付を取得
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        $response = Http::get('https://archive-api.open-meteo.com/v1/archive', [
            'latitude' => 35.42,
            'longitude' => 133.23,
            'start_date' => $yesterday,
            'end_date' => $yesterday,
            'daily' => 'temperature_2m_max',
            'timezone' => 'Asia/Tokyo',
        ]);

        $data = $response->json();
        $temp = $data['daily']['temperature_2m_max'][0];

        // データベースに保存
        WeatherLog::updateOrCreate(
            ['date' => $yesterday],
            ['max_temp' => $temp]
        );

        $this->info("{$yesterday} の気温 {$temp}℃ を保存しました！");
    }
}