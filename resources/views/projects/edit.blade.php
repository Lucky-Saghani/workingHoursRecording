@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Project</h1>

    <form method="POST" action="{{ route('projects.update', $project->id) }}" class="mt-4">
        @csrf
        @method('PUT')

        <div class="mb-3 row">
            <label for="name" class="col-sm-2 col-form-label">Project Name:</label>
            <div class="col-sm-10">
                <input type="text" name="name" id="name" class="form-control" value="{{ $project->name }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('projects.index') }}" class="btn btn-secondary">Back to Projects</a>
            </div>
        </div>
    </form>
</div>
@endsection
