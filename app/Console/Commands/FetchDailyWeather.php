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

    // public function handle()
    // {
    //     // 昨日の日付を取得
    //     $yesterday = Carbon::yesterday()->format('Y-m-d');

    //     $response = Http::get('https://archive-api.open-meteo.com/v1/archive', [
    //         'latitude' => 35.42,
    //         'longitude' => 133.23,
    //         'start_date' => $yesterday,
    //         'end_date' => $yesterday,
    //         'daily' => 'temperature_2m_max',
    //         'timezone' => 'Asia/Tokyo',
    //     ]);

    //     $data = $response->json();
    //     $temp = $data['daily']['temperature_2m_max'][0];

    //     // データベースに保存
    //     WeatherLog::updateOrCreate(
    //         ['date' => $yesterday],
    //         ['max_temp' => $temp]
    //     );

    //     $this->info("{$yesterday} の気温 {$temp}℃ を保存しました！");
    // }
    public function handle()
{
    // 2026年1月1日から昨日までを取得対象にする
    $start = Carbon::create(2026, 1, 1);
    $yesterday = Carbon::yesterday();

    $this->info("1月1日から昨日までのデータを取得します...");

    // 1日ずつループして取得・保存する
    for ($date = $start; $date <= $yesterday; $date->addDay()) {
        $formattedDate = $date->format('Y-m-d');

        $response = Http::get('https://archive-api.open-meteo.com/v1/archive', [
            'latitude' => 35.42,
            'longitude' => 133.23,
            'start_date' => $formattedDate,
            'end_date' => $formattedDate,
            'daily' => 'temperature_2m_max',
            'timezone' => 'Asia/Tokyo',
        ]);

        $data = $response->json();
        
        // データが存在する場合のみ保存
        if (isset($data['daily']['temperature_2m_max'][0])) {
            $temp = $data['daily']['temperature_2m_max'][0];

            WeatherLog::updateOrCreate(
                ['date' => $formattedDate],
                ['max_temp' => $temp]
            );

            $this->info("{$formattedDate}: {$temp}℃ を保存完了");
        }
    }
    $totalTemp = \App\Models\WeatherLog::sum('max_temp');

    // ★ ここから追加：LINE通知のテスト条件
    if ($totalTemp >= 200) { 
        // 累積が200度を超えていたらLINEを送る（テスト用）
        
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(env('LINE_CHANNEL_ACCESS_TOKEN'));
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => env('LINE_CHANNEL_SECRET')]);

        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder(
            "【安来花粉ナビ・テスト】現在の累積温度は " . number_format($totalTemp, 1) . "℃ です！"
        );
        
        $response = $bot->pushMessage(env('LINE_USER_ID'), $textMessageBuilder);
        
        if ($response->isSucceeded()) {
            $this->info("LINE通知を送信しました！");
        } else {
            $this->error("LINE通知の送信に失敗しました: " . $response->getRawBody());
        }
    }

    $this->info("全データの取得が完了しました！");
}
}