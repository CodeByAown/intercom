@extends('admin.master')

@section('title')
    Tickets
@endsection

@section('content')
<div class="main-content">
    <h2>Tickets</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">Add New Ticket</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
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
            <tr>
                <td>{{ $ticket->id }}</td>
                <td>{{ $ticket->date }}</td>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->location }}</td>
                <td>{{ $ticket->wan }}</td>
                <td>{{ $ticket->reason }}</td>
                <td>{{ $ticket->status }}</td>
                <td>
                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    @if($ticket->status === 'active')
                        <form action="{{ route('tickets.close', $ticket->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger btn-sm">Close</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
