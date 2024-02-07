@extends('layouts.app')

@section('content')
    <h1>Projects</h1>

    <a href="{{ route('projects.create') }}">Create New Project</a>

    <ul>
        @foreach($projects as $project)
            <li>
                {{ $project->name }}
                <a href="{{ route('projects.edit', $project->id) }}">Edit</a>
                <form method="POST" action="{{ route('projects.destroy', $project->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
