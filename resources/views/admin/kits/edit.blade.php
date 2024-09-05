@extends('admin.master')

@section('title')
    Edit Kit
@endsection

@section('content')
<div class="main-content">
    <h2>Edit Kit</h2>

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
    <form method="POST" action="{{ route('kits.update', $kit->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="kit_number" class="form-label">Kit Number</label>
            <input type="text" class="form-control" id="kit_number" name="kit_number" value="{{ $kit->kit_number }}" required>
        </div>
        <div class="mb-3">
            <label for="site_id" class="form-label">Site</label>
            <select class="form-control" id="site_id" name="site_id" required>
                <option value="">Select Site</option>
                @foreach($sites as $site)
                    <option value="{{ $site->id }}" {{ $kit->site_id == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
</div>
</div>
@endsection
