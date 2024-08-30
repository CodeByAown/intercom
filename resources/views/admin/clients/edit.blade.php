@extends('admin.master')

@section('title')
    Edit Client
@endsection

@section('content')
<div class="main-content">
    <h2>Edit Client</h2>

    <form method="POST" action="{{ route('clients.update', $client->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Client Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $client->name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
