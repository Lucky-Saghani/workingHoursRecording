@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center"> <!-- Center the "Time Logs" heading -->
            <div class="col-md-6">
                <h1>Time Logs</h1>
            </div>
        </div>
       
        <div class="row mb-3">
            <div class="col-md-6"></div> <!-- Empty column to align buttons to the right -->
            <div class="col-md-6 text-md-end">
                @if(count($timeLogs) != 0)
                    <a href="{{ route('timeLogs.exportCsv') }}" class="btn btn-primary">Export to CSV</a>
                @endif
                @if(count($projects) != 0)
                    <a href="{{ route('timeLogs.create') }}" class="btn btn-primary">Add Time Log</a>
                @endif
                <a href="{{ route('projects.index') }}" class="btn btn-primary">View All Project</a>
                <a href="{{ route('projects.create') }}" class="btn btn-primary">Add New Project</a>
            </div>
        </div>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Project</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($timeLogs) != 0)
                    <?php $i=1; ?>
                    @foreach($timeLogs as $timeLog)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ optional($timeLog->project)->name }}</td>
                            <td>{{ $timeLog->start_time }}</td>
                            <td>{{ $timeLog->end_time }}</td>
                            <td>{{ $timeLog->description }}</td>
                            <td>
                                <a href="{{ route('timeLogs.edit', $timeLog->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form method="POST" action="{{ route('timeLogs.destroy', $timeLog->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this time log?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">No Timelogs added Yet.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="row mb-3">
            <div class="col-md-6"></div> <!-- Empty column to align buttons to the right -->
            <div class="col-md-6 text-md-end">
                @if(count($timeLogs) != 0)
                    <a href="{{ route('timeLogs.evaluation.days') }}" class="btn btn-success">Evaluation Days</a>
                    <a href="{{ route('timeLogs.evaluation.months') }}" class="btn btn-success">Evaluation Months</a>
                    <a href="{{ route('timeLogs.evaluation.project') }}" class="btn btn-success">Evaluation Project</a>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            body {
                font-family: 'Arial', sans-serif;
                margin: 20px;
            }
        </style>
    @endpush
@endsection
