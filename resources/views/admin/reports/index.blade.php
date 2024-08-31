@extends('admin.master')

@section('title')
Reports
@endsection


@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Generate Reports</h1>
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

            <div id="reportResult" class="mt-4">
                <!-- This section will display the generated report -->
            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
<script>
    document.getElementById('reportForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const timePeriod = document.getElementById('timePeriod').value;

        // AJAX call to generate the report (you'll need to implement this endpoint)
        fetch(`/generate-report?timePeriod=${timePeriod}`)
            .then(response => response.json())
            .then(data => {
                // Process and display the report data
                let reportHTML = '<h4>Report for ' + timePeriod.replace(/_/g, ' ') + '</h4>';
                reportHTML += '<table class="table table-bordered"><thead><tr><th>Site</th><th>Data Point</th><th>Status</th></tr></thead><tbody>';

                data.forEach(item => {
                    reportHTML += `<tr>
                        <td>${item.site}</td>
                        <td>${item.dataPoint}</td>
                        <td style="background-color: ${item.statusColor};">${item.status}</td>
                    </tr>`;
                });

                reportHTML += '</tbody></table>';
                document.getElementById('reportResult').innerHTML = reportHTML;
            })
            .catch(error => console.error('Error generating report:', error));
    });
</script>
@endsection
