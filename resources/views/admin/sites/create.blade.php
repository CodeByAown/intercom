@extends('admin.master')

@section('title')
    Create Site
@endsection

@section('content')
<div class="main-content">
    <h2>Create New Site</h2>
    <div class="card">
        <div class="card-body">
    <form method="POST" action="{{ route('sites.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Site Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="client_id" class="form-label">Client</label>
            <select class="form-control" id="client_id" name="client_id" required>
                <option value="">Select Client</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
</div>
</div>
@endsection
