<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('weather_logs', function (Blueprint $table) {
        // 最高気温の隣に、最低気温用の列（小数第2位まで）を追加します
        $table->decimal('min_temp', 5, 2)->nullable()->after('max_temp');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weather_logs', function (Blueprint $table) {
            //
        });
    }
};
