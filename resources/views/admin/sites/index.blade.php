@extends('admin.master')

@section('title')
    Sites
@endsection

@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="main-content">
    <h2>Sites</h2>
    <a href="{{ route('sites.create') }}" class="btn btn-primary mb-3">Add New Site</a>

    <table id="sites-table" class="table table-bordered display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Client</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sites as $site)
            <tr>
                <td>{{ $site->id }}</td>
                <td>{{ $site->name }}</td>
                <td>{{ $site->client->name }}</td>
                <td>
                    <a href="{{ route('sites.show', $site->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('sites.edit', $site->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('sites.destroy', $site->id) }}" method="POST" style="display:inline;">
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

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#sites-table').DataTable();
    });
</script>
@endsection
