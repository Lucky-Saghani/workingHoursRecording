@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-md-6">
            <h1>Projects</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('projects.create') }}" class="btn btn-success">Create New Project</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive"> <!-- Add this div -->
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $project->name }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn btn-warning btn-sm btn-sm me-1">Edit</a>
                                        <form method="POST" action="{{ route('projects.destroy', $project->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No projects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
