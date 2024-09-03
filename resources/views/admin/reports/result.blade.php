@extends('admin.master')

@section('title')
Result
@endsection

@section('style')
<style>
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
        <h2>Report for Client: {{ $client_name }}</h2>
    @else
        <h2>Report for All Clients</h2>
    @endif
        <div class="section-header">
            <h1 class="mb-4">Report Results</h1>

        </div>

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

            <div class="chart-container">
                <canvas id="issueChart"></canvas>
            </div>

            <div class="chart-container">
                <canvas id="barChart"></canvas>
            </div>

            <div class="chart-container">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxPie = document.getElementById('issueChart').getContext('2d');
    const issueCounts = @json($issueCounts);

    const pieData = {
        labels: ['Poor Cable', 'Update Pending', 'Obstruction', 'Login Issue'],
        datasets: [{
            label: 'Issue Frequency',
            data: [issueCounts.poor_cable, issueCounts.update_pending, issueCounts.obstruction, issueCounts.login_issue],
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
                    text: 'Issue Frequency Overview',
                    font: {
                        size: 20
                    }
                }
            }
        },
    };

    const issueChart = new Chart(ctxPie, pieConfig);

    // Bar Chart
    const ctxBar = document.getElementById('barChart').getContext('2d');
    const barData = {
        labels: ['Poor Cable', 'Update Pending', 'Obstruction', 'Login Issue'],
        datasets: [{
            label: 'Issue Frequency',
            data: [issueCounts.poor_cable, issueCounts.update_pending, issueCounts.obstruction, issueCounts.login_issue],
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };

    const barConfig = {
        type: 'bar',
        data: barData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Bar Chart of Issues',
                    font: {
                        size: 20
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
    };

    const barChart = new Chart(ctxBar, barConfig);

    // Line Chart
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    const lineData = {
        labels: ['Poor Cable', 'Update Pending', 'Obstruction', 'Login Issue'],
        datasets: [{
            label: 'Issue Trends',
            data: [issueCounts.poor_cable, issueCounts.update_pending, issueCounts.obstruction, issueCounts.login_issue],
            fill: false,
            borderColor: 'rgba(255, 99, 132, 1)',
            tension: 0.1
        }]
    };

    const lineConfig = {
        type: 'line',
        data: lineData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Line Chart of Issues',
                    font: {
                        size: 20
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
    };

    const lineChart = new Chart(ctxLine, lineConfig);
</script>
@endsection


