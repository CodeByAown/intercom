@extends('admin.master')

@section('title')
Reports
@endsection

@section('content')
<div class="main-content">
    @if(session('message'))
    <div class="alert alert-info mt-3">{{ session('message') }}</div>
@endif
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
        </div>
    </section>
</div>
@endsection
