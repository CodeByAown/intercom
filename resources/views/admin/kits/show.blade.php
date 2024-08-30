@extends('admin.master')

@section('title')
    View Kit
@endsection

@section('content')
<div class="main-content">
    <h2>Kit Details</h2>

    <div class="mb-3">
        <label class="form-label">Kit Number</label>
        <p>{{ $kit->kit_number }}</p>
    </div>
    <div class="mb-3">
        <label class="form-label">Site</label>
        <p>{{ $kit->site->name }}</p>
    </div>

    <a href="{{ route('kits.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
