@extends('admin.master')

@section('title')
    Clients
@endsection

@section('content')
<div class="main-content">
    <h2>Clients</h2>
    <a href="{{ route('clients.create') }}" class="btn btn-primary mb-3">Add New Client</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->name }}</td>
                <td>
                    <a href="{{ route('clients.show', $client->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
