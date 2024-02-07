@extends('layouts.app')

@section('content')
    <h1>Create New Project</h1>

    <form method="POST" action="{{ route('projects.store') }}">
        @csrf

        <label for="name">Project Name:</label>
        <input type="text" name="name" required>

        <button type="submit">Save</button>
    </form>

    <a href="{{ route('projects.index') }}">Back to Projects</a>
@endsection
