@extends('admin.master')

@section('title')
Form
@endsection

@section('style')
<style>
    .warning-box {
        background-color: yellow;
        color: red;
        font-weight: bold;
    }
</style>
@endsection


@section('content')
<div class="main-content">



        <h2>Data Entry Form</h2>

        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('form.save') }}">
            @csrf

            <!-- Date (automatically set to current date) -->
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ \Carbon\Carbon::now()->toDateString() }}" >
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

            <!-- Site Dropdown (dynamically loaded based on client selection) -->
            <div class="mb-3">
                <label for="site" class="form-label">Site</label>
                <select class="form-control" id="site" name="site_id" onchange="loadKits()">
                    <!-- Sites will be loaded here dynamically -->
                </select>
            </div>

            <!-- Kit Dropdown (dynamically loaded based on site selection) -->
            <div class="mb-3">
                <label for="kit" class="form-label">Kit Number</label>
                <select class="form-control" id="kit" name="kit_id">
                    <!-- Kits will be loaded here dynamically -->
                </select>
            </div>

            <!-- Speed Field -->
            <div class="mb-3">
                <label for="speed" class="form-label">Speed</label>
                <input type="text" class="form-control" id="speed" name="speed" onchange="checkForWarnings()">
            </div>

            <!-- Poor Cable Field -->
            <div class="mb-3">
                <label for="poor_cable" class="form-label">Poor Cable</label>
                <select class="form-control" id="poor_cable" name="poor_cable" onchange="checkForWarnings()">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <!-- Update Pending Field -->
            <div class="mb-3">
                <label for="update_pending" class="form-label">Update Pending</label>
                <select class="form-control" id="update_pending" name="update_pending" onchange="checkForWarnings()">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <!-- Obstruction Field -->
            <div class="mb-3">
                <label for="obstruction" class="form-label">Obstruction</label>
                <select class="form-control" id="obstruction" name="obstruction" onchange="checkForWarnings()">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <!-- Login Issue Field -->
            <div class="mb-3">
                <label for="login_issue" class="form-label">Login Issue</label>
                <select class="form-control" id="login_issue" name="login_issue" onchange="checkForWarnings()">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <!-- Warning Box -->
            <div id="warning-box" class="mb-3 p-3" style="display: none;">
                <p>Warning: Check tickets and verify if a ticket is already open. If not, please open a new ticket.</p>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
       </div>


@endsection


@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
                }
            });
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
        }
    }

    function checkForWarnings() {
        var speed = $('#speed').val();
        var poorCable = $('#poor_cable').val();
        var updatePending = $('#update_pending').val();
        var obstruction = $('#obstruction').val();
        var loginIssue = $('#login_issue').val();

        if (speed !== '1gbps' || poorCable !== '0' || updatePending !== '0' || obstruction !== '0' || loginIssue !== '0') {
            $('#warning-box').show().addClass('warning-box');
        } else {
            $('#warning-box').hide().removeClass('warning-box');
        }
    }
</script>
@endsection











