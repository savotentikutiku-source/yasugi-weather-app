<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\WeatherController;

Route::get('/weather', [WeatherController::class, 'index']);

Route::get('/test-line', function () {
    // データ取得コマンドをこのURLにアクセスした時だけ実行する
    Artisan::call('weather:fetch');
    return "安来市のデータ取得とLINE通知コマンドを実行しました！ログを確認してください。";
});
