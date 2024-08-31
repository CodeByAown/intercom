@extends('admin.master')

@section('title')
    View Client
@endsection

@section('content')
<div class="main-content">
    <h2>Client Details</h2>

    <div class="mb-3">
        <label class="form-label">Client Name</label>
        <p>{{ $client->name }}</p>
    </div>

    <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back</a>
</div>

@endsection
