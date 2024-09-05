@extends('admin.master')

@section('title')
Result
@endsection

@section('style')
<style>
    /* Styles for the report page */
    .section-header h1 {
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
    <section class="section">
        @if(request('client_id'))
            <h2 class="text-center">Report for Client: {{ $client_name }}</h2>
        @else
            <h2>Report for All Clients</h2>
        @endif
        <div class="section-header">
            <h1 class="mb-4">Report Results</h1>
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

            <table class="table">
                <thead>
                    <tr>
                        <th>Site Name</th>
                        <th>Speed</th>
                        <th>Poor Cable</th>
                        <th>Update Pending</th>
                        <th>Obstruction</th>
                        <th>Login Issue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                    <tr>
                        <td>{{ $entry->site->name }}</td>
                        <td>{{ $entry->speed }}</td>
                        <td style="background-color: {{ $entry->poor_cable ? 'yellow' : 'white' }}">{{ $entry->poor_cable ? 'Yes' : 'No' }}</td>
                        <td style="background-color: {{ $entry->update_pending ? 'orange' : 'white' }}">{{ $entry->update_pending ? 'Yes' : 'No' }}</td>
                        <td style="background-color: {{ $entry->obstruction ? 'red' : 'white' }}">{{ $entry->obstruction ? 'Yes' : 'No' }}</td>
                        <td style="background-color: {{ $entry->login_issue ? 'purple' : 'white' }}">{{ $entry->login_issue ? 'Yes' : 'No' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Overall Issue Counts Chart -->
            <div class="chart-container">
                <canvas id="overallIssueChart"></canvas>
            </div>

            <!-- Individual Issue Counts Charts -->
            @foreach($issueCountsPerSite as $siteId => $issueCountss)
                <div class="chart-container">
                    <canvas id="issueChart{{ $siteId }}"></canvas>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Overall Issue Counts Chart
        const overallIssueCounts = @json($issueCounts);
        console.log(overallIssueCounts);

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
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1,
                hoverOffset: 4
            }]
        };

        const overallPieConfig = {
            type: 'pie',
            data: overallPieData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Overall Issue Frequency Overview',
                        font: {
                            size: 20
                        }
                    }
                }
            },
        };

        new Chart(ctxOverall, overallPieConfig);

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
                    ],
                    borderColor: [
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1,
                    hoverOffset: 4
                }]
            };

            const pieConfig = {
                type: 'pie',
                data: pieData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Issue Frequency Overview for Site: ' + issueCountss.siteName,
                            font: {
                                size: 20
                            }
                        }
                    }
                }
            };

            new Chart(ctx, pieConfig);
        });
    });
</script>
@endsection
