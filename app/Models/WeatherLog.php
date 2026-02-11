<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherLog extends Model
{
    use HasFactory;

    // ★ 'min_temp' を追加して、保存を許可します
    protected $fillable = ['date', 'max_temp', 'min_temp'];
}