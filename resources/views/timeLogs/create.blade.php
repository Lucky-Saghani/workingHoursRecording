@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Time Log</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('timeLogs.store') }}" class="time-log-form">
            @csrf

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Project:</label>
                <div class="col-sm-10">
                    <select name="project_id" class="form-control" required>
                        <option value="" selected disabled>Please Select a project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="start_time" class="col-sm-2 col-form-label">Start Time:</label>
                <div class="col-sm-10">
                    <input type="datetime-local" id="start_time" name="start_time" class="form-control" required>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="end_time" class="col-sm-2 col-form-label">End Time:</label>
                <div class="col-sm-10">
                    <input type="datetime-local" id="end_time" name="end_time" class="form-control" required>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="description" class="col-sm-2 col-form-label">Description:</label>
                <div class="col-sm-10">
                    <textarea name="description" class="form-control" required></textarea>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary" onclick="return validateTime()">Save</button>
                    <a href="{{ route('timeLogs.index') }}" class="btn btn-secondary">Back to Time Logs</a>
                </div>
            </div>
        </form>
        
        
    </div>

    <script>
        function validateTime() {
            var startTime = document.getElementById('start_time').value;
            var endTime = document.getElementById('end_time').value;

            if (startTime >= endTime) {
                alert('End time must be greater than start time.');
                return false;
            }

            return true;
        }
    </script>
@endsection
