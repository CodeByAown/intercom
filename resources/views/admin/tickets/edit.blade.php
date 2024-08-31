@extends('admin.master')

@section('title')
    Edit Ticket
@endsection

@section('content')
<div class="main-content">
    <h2>Edit Ticket</h2>

    <form method="POST" action="{{ route('tickets.update', $ticket->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ $ticket->date }}" required>
        </div>
        <div class="mb-3">
            <label for="ticket_number" class="form-label">Ticket Number</label>
            <input type="text" class="form-control" id="ticket_number" name="ticket_number" value="{{ $ticket->ticket_number }}" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ $ticket->location }}" required>
        </div>
        <div class="mb-3">
            <label for="wan" class="form-label">WAN</label>
            <input type="text" class="form-control" id="wan" name="wan" value="{{ $ticket->wan }}" required>
        </div>
        <div class="mb-3">
            <label for="reason" class="form-label">Reason</label>
            <textarea class="form-control" id="reason" name="reason" rows="3" required>{{ $ticket->reason }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
