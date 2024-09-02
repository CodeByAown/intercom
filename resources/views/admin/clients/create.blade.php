@extends('admin.master')

@section('title')
    Create Client
@endsection

@section('content')
<div class="main-content">
    <h2>Create New Client</h2>
    <div class="card">
        <div class="card-body">
    <form method="POST" action="{{ route('clients.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Client Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
</div>
</div>
@endsection
