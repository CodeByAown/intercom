@extends('admin.master')

@section('title')
    Edit Client
@endsection

@section('content')
<div class="main-content">
    <h2>Edit Client</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="card">
        <div class="card-body">
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
</div>
</div>
@endsection
