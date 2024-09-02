@extends('admin.master')

@section('title')
    View Ticket
@endsection

@section('content')
<div class="main-content">
    <h2>Ticket Details</h2>
    <div class="card">
        <div class="card-body">
    <div class="mb-3">
        <label class="form-label">Date</label>
        <p>{{ $ticket->date }}</p>
    </div>
    <div class="mb-3">
        <label class="form-label">Ticket Number</label>
        <p>{{ $ticket->ticket_number }}</p>
    </div>
    <div class="mb-3">
        <label class="form-label">Location</label>
        <p>{{ $ticket->location }}</p>
    </div>
    <div class="mb-3">
        <label class="form-label">WAN</label>
        <p>{{ $ticket->wan }}</p>
    </div>
    <div class="mb-3">
        <label class="form-label">Reason</label>
        <p>{{ $ticket->reason }}</p>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <p>{{ $ticket->status }}</p>
    </div>
    <div class="card">
        <div class="card-body">
    <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
