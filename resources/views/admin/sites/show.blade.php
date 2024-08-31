@extends('admin.master')

@section('title')
    View Site
@endsection

@section('content')
<div class="main-content">
    <h2>Site Details</h2>

    <div class="mb-3">
        <label class="form-label">Site Name</label>
        <p>{{ $site->name }}</p>
    </div>
    <div class="mb-3">
        <label class="form-label">Client</label>
        <p>{{ $site->client->name }}</p>
    </div>

    <a href="{{ route('sites.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
