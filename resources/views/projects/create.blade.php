@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Project</h1>

    <form method="POST" action="{{ route('projects.store') }}" class="mt-4">
        @csrf

        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Project Name:</label>
            <div class="col-sm-10">
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"></div> <!-- Offset column to align button with input field -->
            <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('projects.index') }}" class="btn btn-secondary ms-2">Back to Projects</a>
            </div>
        </div>
    </form>
</div>
@endsection
