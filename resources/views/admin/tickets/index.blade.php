@extends('admin.master')

@section('title', 'Tickets')

@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="main-content">
    <h2>Tickets</h2>

    <div class="mb-3">
        <label for="closed-tickets" class="form-check-label">
            <input type="checkbox" id="closed-tickets"> Closed Tickets
        </label>
    </div>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">Add New Ticket</a>
    <div class="card">
        <div class="card-body">
            @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
    <table id="tickets-table" class="display">
        <thead>
            <tr>
                <th>Date</th>
                <th>Ticket Number</th>
                <th>Location</th>
                <th>WAN</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr data-status="{{ $ticket->status }}">
                    <td>{{ $ticket->date }}</td>
                    <td>{{ $ticket->ticket_number }}</td>
                    <td>{{ $ticket->location }}</td>
                    <td>{{ $ticket->wan }}</td>
                    <td>{{ $ticket->reason }}</td>
                    <td>
                        @if($ticket->status === 'active')
                            <i class="text-success">● Active</i>
                        @else
                            <i class="text-danger">● Closed</i>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-primary btn-sm">Delete</button>
                        </form>
                        @if($ticket->status === 'active')
                            <form action="{{ route('tickets.close', $ticket->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger btn-sm">Close</button>
                            </form>
                        @else
                            <form action="{{ route('tickets.reopen', $ticket->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm">Activate</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    </div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#tickets-table').DataTable();

        $('#closed-tickets').change(function() {
            if ($(this).is(':checked')) {
                table.rows().every(function() {
                    var status = $(this.node()).data('status');
                    if (status === 'active') {
                        $(this.node()).hide();
                    } else {
                        $(this.node()).show();
                    }
                });
            } else {
                table.rows().show();
            }
        });
    });
</script>
@endsection
