@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Worked Hours Evaluation</h1>

        <!-- Table View -->
        <div class="mb-4">
            <h2>Table View</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Worked Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($workedHours as $date => $hours)
                            <tr>
                                <td>{{ $date }}</td>
                                <td>{{ $hours }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chart View -->
        <div class="mb-4">
            <h2>Chart View</h2>
            <canvas id="workedHoursChart" width="400" height="200"></canvas>
        </div>

        <!-- Back to Time Logs -->
        <a href="{{ route('timeLogs.index') }}" class="btn btn-secondary">Back to Time Logs</a>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('workedHoursChart').getContext('2d');
        var workedHoursChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($workedHours->toArray())) !!},
                datasets: [{
                    label: 'Worked Hours',
                    data: {!! json_encode(array_values($workedHours->toArray())) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
