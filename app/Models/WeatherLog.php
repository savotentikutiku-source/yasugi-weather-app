<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherLog extends Model
{
    use HasFactory;

    // 以下の1行を追加することで、dateとmax_tempを保存できるようになります
    protected $fillable = ['date', 'max_temp'];
}