@extends('admin.master')

@section('title')
    Create Ticket
@endsection

@section('content')
<div class="main-content">
    <h2>Create Ticket</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('tickets.store') }}">
        @csrf

        <!-- Date -->
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" required value="{{ \Carbon\Carbon::now()->toDateString() }}" >
        </div>

        <!-- Ticket Number -->
        <div class="mb-3">
            <label for="ticket_number" class="form-label">Ticket Number</label>
            <input type="text" class="form-control" id="ticket_number" name="ticket_number" required>
        </div>

        <!-- Location -->
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" required>
        </div>

        <!-- WAN -->
        <div class="mb-3">
            <label for="wan" class="form-label">WAN</label>
            <input type="text" class="form-control" id="wan" name="wan" required>
        </div>

        <!-- Reason -->
        <div class="mb-3">
            <label for="reason" class="form-label">Reason</label>
            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Save Ticket</button>
    </form>
</div>
@endsection
