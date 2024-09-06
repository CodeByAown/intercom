@extends('admin.master')

@section('title')
Entry Form
@endsection

@section('style')
<style>
    #warning-box {
        background-color: yellow;
        color: red;
        font-weight: bold;
    }
    .inline-inputs {
        display: flex;
        gap: 1rem; /* Adjust the spacing as needed */
        flex-wrap: wrap; /* Allow wrapping for smaller screens */
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <h2 class="mb-4">Data Entry Form</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('form.save') }}">
                @csrf

                <!-- Date (automatically set to current date) -->
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ old('date', \Carbon\Carbon::now()->toDateString()) }}">
                </div>

                <!-- Client Dropdown -->
                <div class="mb-3">
                    <label for="client" class="form-label">Client</label>
                    <select class="form-control" id="client" name="client_id" onchange="loadSites()">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
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
        </div>
    </div>

    {{-- Speed Section --}}
    <div class="card">
        <div class="card-body">
            <h3>Speed Details</h3>
            <div class="inline-inputs">
                <div class="mb-3">
                    <label for="speed_10mbps" class="form-label">10MBPS Speed</label>
                    <input type="number" class="form-control" id="speed_10mbps" name="speed_10mbps" min="0" value="{{ old('speed_10mbps') }}">
                </div>
                <div class="mb-3">
                    <label for="speed_100mbps" class="form-label">100MBPS Speed</label>
                    <input type="number" class="form-control" id="speed_100mbps" name="speed_100mbps" min="0" value="{{ old('speed_100mbps') }}">
                </div>
                <div class="mb-3">
                    <label for="speed_1gbps" class="form-label">1GBPS Speed</label>
                    <input type="number" class="form-control" id="speed_1gbps" name="speed_1gbps" min="0" value="{{ old('speed_1gbps') }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="current_speed" class="form-label">Current Speed</label>
                <select class="form-control" id="current_speed" name="speed" onchange="checkForWarnings()">
                    <option value="1Gbps" {{ old('speed') == '1Gbps' ? 'selected' : '' }}>1Gbps</option>
                    <option value="10Mbps" {{ old('speed') == '10Mbps' ? 'selected' : '' }}>10Mbps</option>
                    <option value="100Mbps" {{ old('speed') == '100Mbps' ? 'selected' : '' }}>100Mbps</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Poor Cable Section -->
    <div class="card">
        <div class="card-body">
            <h3>Poor Cable Details</h3>
            <div class="inline-inputs">
                <div class="mb-3">
                    <label for="yes_field" class="form-label">Yes</label>
                    <input type="number" class="form-control" id="yes_field" name="yes_cablefield" min="0" value="{{ old('yes_cablefield') }}">
                </div>
                <div class="mb-3">
                    <label for="no_field" class="form-label">No</label>
                    <input type="number" class="form-control" id="no_field" name="no_cablefield" min="0" value="{{ old('no_cablefield') }}">
                </div>
                <div class="mb-3">
                    <label for="current_poor_cable" class="form-label">Current</label>
                    <select class="form-control" id="current_poor_cable" name="poor_cable" onchange="checkForWarnings()">
                        <option value="0" {{ old('poor_cable') == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('poor_cable') == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Pending Details Section -->
    <div class="card">
        <div class="card-body">
            <h3>Update Pending Details</h3>
            <div class="inline-inputs">
                <div class="mb-3">
                    <label for="auto_reboot" class="form-label">Auto Reboot</label>
                    <input type="number" class="form-control" id="auto_reboot" name="auto_reboot" min="0" value="{{ old('auto_reboot') }}">
                </div>
                <div class="mb-3">
                    <label for="manual_reboot" class="form-label">Manual Reboot</label>
                    <input type="number" class="form-control" id="manual_reboot" name="manual_reboot" min="0" value="{{ old('manual_reboot') }}">
                </div>
                <div class="mb-3">
                    <label for="no_updatepending" class="form-label">No</label>
                    <input type="number" class="form-control" id="no_updatepending" name="no_updatepending" min="0" value="{{ old('no_updatepending') }}">
                </div>
                <div class="mb-3">
                    <label for="current_update_pending" class="form-label">Current</label>
                    <select class="form-control" id="current_update_pending" name="update_pending" onchange="checkForWarnings()">
                        <option value="0" {{ old('update_pending') == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('update_pending') == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Obstruction Section --}}
    <div class="card">
        <div class="card-body">
            <h3>Obstruction Details</h3>
            <div class="inline-inputs">
                <div class="mb-3">
                    <label for="full_obstruction" class="form-label">Full</label>
                    <input type="number" class="form-control" id="full_obstruction" name="full_obstruction" min="0" value="{{ old('full_obstruction') }}">
                </div>
                <div class="mb-3">
                    <label for="partial_obstruction" class="form-label">Partial</label>
                    <input type="number" class="form-control" id="partial_obstruction" name="partial_obstruction" min="0" value="{{ old('partial_obstruction') }}">
                </div>
                <div class="mb-3">
                    <label for="no_obstruction" class="form-label">No</label>
                    <input type="number" class="form-control" id="no_obstruction" name="no_obstruction" min="0" value="{{ old('no_obstruction') }}">
                </div>
                <div class="mb-3">
                    <label for="current_obstruction" class="form-label">Current</label>
                    <select class="form-control" id="current_obstruction" name="obstruction" onchange="checkForWarnings()">
                        <option value="0" {{ old('obstruction') == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('obstruction') == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3>Login Issues</h3>
            <div class="inline-inputs">
                <div class="mb-3">
                    <label for="yes_login_issue" class="form-label">Yes</label>
                    <input type="number" class="form-control" id="yes_login_issue" name="yes_login_issue" min="0" value="{{ old('yes_login_issue') }}">
                </div>
                <div class="mb-3">
                    <label for="no_login_issue" class="form-label">No</label>
                    <input type="number" class="form-control" id="no_login_issue" name="no_login_issue" min="0" value="{{ old('no_login_issue') }}">
                </div>
                <div class="mb-3">
                    <label for="current_login_issue" class="form-label">Current</label>
                    <select class="form-control" id="current_login_issue" name="login_issue" onchange="checkForWarnings()">
                        <option value="0" {{ old('login_issue') == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('login_issue') == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Save</button>

</form>

    <!-- Warning Box -->
    <div id="warning-box" class="mt-5 p-3 mb-2" style="display: none;">
        <p>Warning: Check tickets and verify if a ticket is already open. If not, please open a new ticket.</p>
    </div>

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
                data: {
                    client_id: clientId
                },
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
                data: {
                    site_id: siteId
                },
                success: function(data) {
                    $('#kit').html(data);
                }
            });
        } else {
            $('#kit').html('<option value="">Select Kit</option>'); // Reset kits
        }
    }

    function checkForWarnings() {
        var speed = $('#current_speed').val();
        var poorCable = $('#current_poor_cable').val();
        var updatePending = $('#current_update_pending').val();
        var obstruction = $('#current_obstruction').val();
        var loginIssue = $('#current_login_issue').val();

        if (speed !== '1Gbps' || poorCable === '1' || updatePending === '1' || obstruction === '1' || loginIssue === '1') {
            $('#warning-box').show();
        } else {
            $('#warning-box').hide();
        }
    }
</script>
@endsection
