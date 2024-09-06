@extends('admin.master')

@section('title')
Result
@endsection

@section('style')
<style>
    /* Styles for the report page */
    .section-header h1 , #blueheading {
        color: #4A90E2;
        margin-bottom: 20px;
    }
    .btn-success {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 5px;
    }
    .table {
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    th, td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
    }
    th {
        background-color: #4A90E2;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
    .chart-container {
        position: relative;
        margin: auto;
        max-width: 600px;
        margin-top: 30px;
    }
</style>
@endsection

@section('content')
<div class="main-content">

    @php
    // Define a mapping for time periods
    $timePeriodMapping = [
        'last_week' => 'Last Week',
        'last_month' => 'Last Month',
        'last_12_months' => 'Last 12 Months'
    ];

    // Get the readable time period
    $readableTimePeriod = $timePeriodMapping[request('time_period')] ?? 'Unknown Time Period';
    @endphp

    @if(request('client_id'))
        <h1 class="text-center mb-5">{{ $readableTimePeriod }} Report for Client: {{ $client_name }}</h1>
    @else
        <h1>Report for All Clients</h1>
    @endif

    <section class="form-section">
        <div class="section-header">
            <h2 id="blueheading" class="">Generate Reports:</h2>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Select Time Period</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('reports.generate') }}">
                        @csrf
                        <div class="form-group">
                            <label for="client_id">Select Client</label>
                            <select class="form-control" id="client_id" name="client_id">
                                {{-- <option value="">All Clients</option> --}}
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="time_period">Select Time Period</label>
                            <select class="form-control" id="time_period" name="time_period" required>
                                <option value="last_week">Last Week</option>
                                <option value="last_month">Last Month</option>
                                <option value="last_12_months">Last 12 Months</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Generate Report</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="section-chart">
        <div class="section-header">
            <h2 class="mb-4" id="blueheading">Select Site to View Issue Chart:</h2>
        </div>
        <div class="section-body">
            <!-- Dropdown for selecting site -->
            <div class="form-group mb-3">
                <label for="siteSelect">Select Site</label>
                <select id="siteSelect" class="form-control" onchange="showChart(this.value)">
                    <option value="">Select a site</option>
                    @foreach($issueCountsPerSite as $siteId => $issueCountss)
                        @if($issueCountss['poor_cable'] > 0 || $issueCountss['update_pending'] > 0 || $issueCountss['obstruction'] > 0 || $issueCountss['login_issue'] > 0)
                            <option value="{{ $siteId }}">{{ $issueCountss['siteName'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Individual Issue Counts Charts -->
            @foreach($issueCountsPerSite as $siteId => $issueCountss)
                <div class="chart-container" id="chartContainer{{ $siteId }}" style="display: none;">
                    <canvas id="issueChart{{ $siteId }}" width="400" height="200"></canvas>
                </div>
            @endforeach
        </div>
    </section>

    <section class="section">
        <div class="section-header">
            <h2 class="mb-4" id="blueheading">Report Results:</h2>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="section-body">
            <form method="POST" action="{{ route('reports.export') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="client_id" value="{{ request('client_id')}}">
                <input type="hidden" name="time_period" value="{{ request('time_period') }}">
                <button type="submit" class="btn btn-success mb-3">Export to PDF</button>
            </form>

            <table class="table table-responsive" id="dataTable">
                <thead>
                    <tr>
                        <th rowspan="2">LOCATION</th>
                        <th rowspan="2">Kit Number</th> 
                        <th colspan="4">Speed</th>
                        <th colspan="3">Poor Cable</th>
                        <th colspan="4">Update Pending</th>
                        <th colspan="4">Obstruction</th>
                        <th colspan="3">Login Issue</th>
                    </tr>
                    <tr>
                        <th>10MBPS</th>
                        <th>100MBPS</th>
                        <th>1GBPS</th>
                        <th>Current</th>
                        <th>YES</th>
                        <th>NO</th>
                        <th>Current</th>
                        <th>Auto Reboot</th>
                        <th>Manual Reboot</th>
                        <th>No</th>
                        <th>Current</th>
                        <th>Full</th>
                        <th>Partial</th>
                        <th>No</th>
                        <th>Current</th>
                        <th>Yes</th>
                        <th>No</th>
                        <th>Current</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                        <tr>
                            <td>{{ $entry->site->name }}</td>
                            <td>{{ $entry->kit->kit_number }}</td>
                            <td>{{ $entry->speed_10mbps }}</td>
                            <td>{{ $entry->speed_100mbps }}</td>
                            <td>{{ $entry->speed_1gbps }}</td>
                            <td>{{ $entry->speed }}</td>
                            <td>{{ $entry->yes_cablefield }}</td>
                            <td>{{ $entry->no_cablefield }}</td>
                            <td>{{ $entry->poor_cable ? 'Yes' : 'No'  }}</td>
                            <td>{{ $entry->auto_reboot }}</td>
                            <td>{{ $entry->manual_reboot }}</td>
                            <td>{{ $entry->no_updatepending }}</td>
                            <td>{{ $entry->update_pending ? 'Yes' : 'No' }}</td>
                            <td>{{ $entry->full_obstruction }}</td>
                            <td>{{ $entry->partial_obstruction }}</td>
                            <td>{{ $entry->no_obstruction }}</td>
                            <td>{{ $entry->obstruction ? 'Yes' : 'No' }}</td>
                            <td>{{ $entry->yes_login_issue }}</td>
                            <td>{{ $entry->no_login_issue }}</td>
                            <td>{{ $entry->login_issue ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total:{{ $entries->sum('speed_10mbps') }}</td>
                        <td>Total:{{ $entries->sum('speed_100mbps') }}</td>
                        <td>Total:{{ $entries->sum('speed_1gbps') }}</td>
                        <td></td>
                        <td>Total:{{ $entries->sum('yes_cablefield') }}</td>
                        <td>Total:{{ $entries->sum('no_cablefield') }}</td>
                        <td></td>
                        <td>Total:{{ $entries->sum('auto_reboot') }}</td>
                        <td>Total:{{ $entries->sum('manual_reboot') }}</td>
                        <td>Total:{{ $entries->sum('no_updatepending') }}</td>
                        <td></td>
                        <td>Total:{{ $entries->sum('full_obstruction') }}</td>
                        <td>Total:{{ $entries->sum('partial_obstruction') }}</td>
                        <td>Total:{{ $entries->sum('no_obstruction') }}</td>
                        <td></td>
                        <td>Total:{{ $entries->sum('yes_login_issue') }}</td>
                        <td>Total:{{ $entries->sum('no_login_issue') }}</td>
                        <td></td>
                    </tr>
                </tfoot>

            </table>


            <!-- Overall Issue Counts Chart -->
            <div class="chart-container">
                <canvas id="overallIssueChart"></canvas>
            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Overall Issue Counts Chart
        const overallIssueCounts = @json($issueCounts);
        const ctxOverall = document.getElementById('overallIssueChart').getContext('2d');
        const overallPieData = {
            labels: ['Poor Cable', 'Update Pending', 'Obstruction', 'Login Issue'],
            datasets: [{
                label: 'Overall Issue Frequency',
                data: [
                    overallIssueCounts.poor_cable,
                    overallIssueCounts.update_pending,
                    overallIssueCounts.obstruction,
                    overallIssueCounts.login_issue
                ],
                backgroundColor: [
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(75, 192, 192, 0.6)'
                ]
            }]
        };

        new Chart(ctxOverall, {
            type: 'pie',
            data: overallPieData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Overall Issue Frequency Overview',
                    }
                }
            }
        });

        // Individual Issue Counts Charts
        const issueCountsData = @json($issueCountsPerSite);
        Object.keys(issueCountsData).forEach(siteId => {
            const issueCountss = issueCountsData[siteId];
            const ctx = document.getElementById('issueChart' + siteId).getContext('2d');

            const pieData = {
                labels: ['Poor Cable', 'Update Pending', 'Obstruction', 'Login Issue'],
                datasets: [{
                    label: 'Issue Frequency',
                    data: [
                        issueCountss.poor_cable,
                        issueCountss.update_pending,
                        issueCountss.obstruction,
                        issueCountss.login_issue
                    ],
                    backgroundColor: [
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(75, 192, 192, 0.6)'
                    ]
                }]
            };

            new Chart(ctx, {
                type: 'pie',
                data: pieData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Issue Frequency for Site: ' + issueCountss.siteName,
                        }
                    }
                }
            });
        });
    });

    function showChart(siteId) {
        // Hide all charts
        @foreach($issueCountsPerSite as $siteId => $issueCountss)
            document.getElementById('chartContainer{{ $siteId }}').style.display = 'none';
        @endforeach

        // Show selected chart
        if (siteId) {
            document.getElementById('chartContainer' + siteId).style.display = 'block';
        }
    }
</script>
@endsection
