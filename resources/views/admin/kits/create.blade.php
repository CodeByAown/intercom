@extends('admin.master')

@section('title')
    Create Kit
@endsection

@section('content')
<div class="main-content">
    <h2>Create New Kit</h2>



    <form method="POST" action="{{ route('kits.store') }}">
        @csrf
        <div class="mb-3">
            <label for="kit_number" class="form-label">Kit Number</label>
            <input type="text" class="form-control" id="kit_number" name="kit_number" value="{{$uniqueKitNumber}}" required>
        </div>
        <div class="mb-3">
            <label for="site_id" class="form-label">Site</label>
            <select class="form-control" id="site_id" name="site_id" required>
                <option value="">Select Site</option>
                @foreach($sites as $site)
                    <option value="{{ $site->id }}">{{ $site->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
