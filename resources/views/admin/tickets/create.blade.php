@extends('admin.master')

@section('title')
    Create Ticket
@endsection

@section('content')
<div class="main-content">
    <h2>Create Ticket</h2>
    <div class="card">
        <div class="card-body">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('tickets.store') }}">
        @csrf

        <!-- Date -->
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" required value="{{ \Carbon\Carbon::now()->toDateString() }}">
        </div>

        <!-- Ticket Number -->
        <div class="mb-3">
            <label for="ticket_number" class="form-label">Ticket Number</label>
            <input type="text" class="form-control" id="ticket_number" name="ticket_number" value="{{date('Ymd') . '-' . uniqid()}}" required>
        </div>

         <!-- Client Dropdown -->
         <div class="mb-3">
            <label for="client" class="form-label">Client</label>
            <select class="form-control" id="client" name="client_id" onchange="loadSites()">
                <option value="">Select Client</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Site Dropdown -->
        <div class="mb-3">
            <label for="site" class="form-label">Site</label>
            <select class="form-control" id="site" name="site_id" onchange="loadKits()">
                <option value="">Select Site</option>
            </select>
        </div>

        <!-- Kit Dropdown -->
        <div class="mb-3">
            <label for="kit" class="form-label">Kit Number</label>
            <select class="form-control" id="kit" name="kit_id">
                <option value="">Select Kit</option>
            </select>
        </div>

        

        <!-- Location -->
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" required>
        </div>

        <!-- WAN -->
        <div class="mb-3">
            <label for="wan" class="form-label">WAN</label>
            <input type="text" class="form-control" id="wan" name="wan" required>
        </div>

        <!-- Reason -->
        <div class="mb-3">
            <label for="reason" class="form-label">Reason</label>
            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Save Ticket</button>
    </form>


</div>
</div>
</div>
@endsection


@section('js')

<script>
function loadSites() {
        var clientId = $('#client').val();
        if (clientId) {
            $.ajax({
                url: '{{ route("getSites") }}',
                type: 'GET',
                data: { client_id: clientId },
                success: function(data) {
                    $('#site').html(data);
                    $('#kit').html('<option value="">Select Kit</option>'); // Reset kits
                }
            });
        } else {
            $('#site').html('<option value="">Select Site</option>');
            $('#kit').html('<option value="">Select Kit</option>'); // Reset kits
        }
    }

    function loadKits() {
        var siteId = $('#site').val();
        if (siteId) {
            $.ajax({
                url: '{{ route("getKits") }}',
                type: 'GET',
                data: { site_id: siteId },
                success: function(data) {
                    $('#kit').html(data);
                }
            });
        } else {
            $('#kit').html('<option value="">Select Kit</option>'); // Reset kits
        }
    }
</script>
@endsection
