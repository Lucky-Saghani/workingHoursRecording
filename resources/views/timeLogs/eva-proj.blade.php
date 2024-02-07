@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Worked Hours Evaluation</h1>

        <!-- Table View -->
        <div class="mb-4">
            <h2>Table View</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Month</th>
                            <th>Worked Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($workedHours as $project => $project_details)
                            @foreach($project_details as $project_data)
                                <tr>
                                    <td>{{$project}}</td>
                                    <td>{{$project_data->month}}</td>
                                    <td>{{$project_data->total_hours}}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chart View -->
        <div class="mb-4">
            <h2>Chart View</h2>
            <div class="row">
                @foreach ($workedHours as $projectName => $projectData)
                    <div class="col-md-6">
                        <h3>{{ $projectName }}</h3>
                        @php
                            $chartId = str_replace(' ', '_', $projectName);
                        @endphp
                        <canvas id="{{ $chartId }}Chart" width="400" height="200"></canvas>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Back to Time Logs -->
        <a href="{{ route('timeLogs.index') }}" class="btn btn-secondary">Back to Time Logs</a>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach ($workedHours as $projectName => $projectData)
                var ctx = document.getElementById('{{ str_replace(' ', '_', $projectName) }}Chart').getContext('2d');
                var {{ str_replace(' ', '_', $projectName) }}Chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($projectData->pluck('month')->toArray()) !!},
                        datasets: [{
                            label: 'Worked Hours',
                            data: {!! json_encode($projectData->pluck('total_hours')->toArray()) !!},
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                type: 'category',
                                labels: {!! json_encode($projectData->pluck('month')->toArray()) !!},
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            @endforeach
        });
    </script>
@endsection
