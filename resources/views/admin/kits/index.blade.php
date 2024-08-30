@extends('admin.master')

@section('title')
    Kits
@endsection

@section('content')
<div class="main-content">
    <h2>Kits</h2>
    <a href="{{ route('kits.create') }}" class="btn btn-primary mb-3">Add New Kit</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kit Number</th>
                <th>Site</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kits as $kit)
            <tr>
                <td>{{ $kit->id }}</td>
                <td>{{ $kit->kit_number }}</td>
                <td>{{ $kit->site->name }}</td>
                <td>
                    <a href="{{ route('kits.show', $kit->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('kits.edit', $kit->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('kits.destroy', $kit->id) }}" method="POST" style="display:inline;">
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
