@extends('admin.master')

@section('title')
    Create Ticket
@endsection

@section('content')
<div class="main-content">
    <h2>Create New Ticket</h2>

    <form method="POST" action="{{ route('tickets.store') }}">
        @csrf
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <div class="mb-3">
            <label for="ticket_number" class="form-label">Ticket Number</label>
            <input type="text" class="form-control" id="ticket_number" name="ticket_number" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" required>
        </div>
        <div class="mb-3">
            <label for="wan" class="form-label">WAN</label>
            <input type="text" class="form-control" id="wan" name="wan" required>
        </div>
        <div class="mb-3">
            <label for="reason" class="form-label">Reason</label>
            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
