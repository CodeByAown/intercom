@extends('admin.master')

@section('title')
Entries
@endsection

@section('content')
<div class="main-content">
    <h2>Total Entries</h2>
                <!-- Add Entry Button -->
                <div class="my-3">
                    <a href="{{ route('form.index') }}" class="btn btn-primary">Add Entry</a>
                </div>
    <div class="card">
        <div class="card-body">
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <table id="forms-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Site</th>
                        <th>Kit Number</th>
                        <th>Speed</th>
                        <th>Poor Cable</th>
                        <th>Update Pending</th>
                        <th>Obstruction</th>
                        <th>Login Issue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                        <tr>
                            <td>{{ $entry->date }}</td>
                            <td>{{ $entry->client->name }}</td>
                            <td>{{ $entry->site->name }}</td>
                            <td>{{ $entry->kit->kit_number }}</td>
                            <td>{{ $entry->speed }}</td>
                            <td>{{ $entry->poor_cable ? 'Yes' : 'No' }}</td>
                            <td>{{ $entry->update_pending ? 'Yes' : 'No' }}</td>
                            <td>{{ $entry->obstruction ? 'Yes' : 'No' }}</td>
                            <td>{{ $entry->login_issue ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#forms-table').DataTable();
    });
</script>
@endsection
