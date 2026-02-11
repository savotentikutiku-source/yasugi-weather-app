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