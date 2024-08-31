@extends('admin.master')

@section('title')
Result
@endsection

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Report Results</h1>
        </div>

        <div class="section-body">
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

            <canvas id="issueChart" width="400" height="400"></canvas>
        </div>
    </section>
</div>
@endsection


@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('issueChart').getContext('2d');
    const issueCounts = @json($issueCounts);

    const data = {
        labels: ['Poor Cable', 'Update Pending', 'Obstruction', 'Login Issue'],
        datasets: [{
            label: 'Issue Frequency',
            data: [issueCounts.poor_cable, issueCounts.update_pending, issueCounts.obstruction, issueCounts.login_issue],
            backgroundColor: [
                'rgba(255, 206, 86, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
                'rgba(255, 206, 86, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    };

    const config = {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Issue Frequency Overview'
                }
            }
        },
    };

    const issueChart = new Chart(ctx, config);
</script>

@endsection

{{-- section ended --}}
