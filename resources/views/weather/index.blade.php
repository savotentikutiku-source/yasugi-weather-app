<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>安来市 累積温度＆花粉予報</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; background: #f4f7f6; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h1 { color: #2c3e50; text-align: center; }
        .total { font-size: 2em; color: #e67e22; text-align: center; font-weight: bold; }
        .alert { background: #ffeb3b; padding: 10px; border-radius: 5px; text-align: center; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>
    <h1>安来市 累積温度計</h1>

    <div class="card">
        <div class="total">現在の累積温度: {{ number_format($totalTemp, 1) }} ℃</div>
        
        @if($totalTemp >= 400)
            <div class="alert">【警告】積算400℃突破！スギ花粉の飛散が始まる可能性があります！</div>
        @else
            <div style="text-align:center;">スギ花粉飛散（400℃）まであと: {{ number_format(400 - $totalTemp, 1) }} ℃</div>
        @endif
    </div>
    <div class="card">
        <div class="total">現在の累積温度（スギ）: {{ number_format($totalTemp, 1) }} ℃</div>
        <div class="total" style="color: #8b4513;">ブタクサ用累積（8月〜）: {{ number_format($ragweedTemp, 1) }} ℃</div>
    </div>
    <div class="alerts" style="margin-top: 20px;">
    @if($totalTemp >= 400)
        <div style="background-color: #ffcccc; color: #cc0000; padding: 15px; border-radius: 8px; border: 2px solid #cc0000; font-weight: bold; margin-bottom: 10px;">
            【警報】スギ花粉の飛散目安（400℃）に達しました！対策を開始してください。
        </div>
    @elseif($totalTemp >= 300)
        <div style="background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; border: 1px solid #ffeeba; margin-bottom: 10px;">
            【注意】累積温度が300℃を超えました。まもなくスギ花粉が飛び始めます。
        </div>
    @else
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; border: 1px solid #c3e6cb; margin-bottom: 10px;">
            【安全】スギ花粉の飛散目安まで、あと {{ number_format(400 - $totalTemp, 1) }} ℃ です。
        </div>
    @endif

    @if($ragweedTemp >= 500)
        <div style="background-color: #ffccf2; color: #990066; padding: 15px; border-radius: 8px; border: 2px solid #990066; font-weight: bold;">
            【警告】ブタクサ花粉の飛散目安に達しました！秋の花粉症に注意してください。
        </div>
    @elseif($ragweedTemp > 0)
        <div style="background-color: #e2e3e5; color: #383d41; padding: 15px; border-radius: 8px; border: 1px solid #d6d8db;">
            ブタクサ累積計算中...（現在 {{ number_format($ragweedTemp, 1) }} ℃）
        </div>
    @endif
</div>

    <div class="card">
        <canvas id="weatherChart"></canvas>
    </div>

    <div class="card">
        <h3>日別最高気温リスト</h3>
        <table>
            <thead>
                <tr>
                    <th>日付</th>
                    <th>最高気温 (℃)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>{{ $log->date }}</td>
                    <td>{{ $log->max_temp }} ℃</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        const ctx = document.getElementById('weatherChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($logs->pluck('date')) !!},
                datasets: [{
                    label: '最高気温 (℃)',
                    data: {!! json_encode($logs->pluck('max_temp')) !!},
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            }
        });
    </script>
</body>
</html>