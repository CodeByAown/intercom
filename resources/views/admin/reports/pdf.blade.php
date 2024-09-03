{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #4A90E2;
            margin-bottom: 20px;
        }
        h2 {
            color: #4A90E2;
            margin-top: 30px;
        }
        .alert {
            padding: 10px;
            background-color: #e7f3fe;
            border: 1px solid #b3d7ff;
            color: #31708f;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4A90E2;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e1f5fe;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            background-color: #f9f9f9;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

    @if(session('message'))
    <div class="alert">{{ session('message') }}</div>
    @endif

    <h1>Report Results</h1>

    <table>
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
                <td>{{ $entry->poor_cable ? 'Yes' : 'No' }}</td>
                <td>{{ $entry->update_pending ? 'Yes' : 'No' }}</td>
                <td>{{ $entry->obstruction ? 'Yes' : 'No' }}</td>
                <td>{{ $entry->login_issue ? 'Yes' : 'No' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Issue Counts</h2>
    <ul>
        <li>Poor Cable: {{ $issueCounts['poor_cable'] }}</li>
        <li>Update Pending: {{ $issueCounts['update_pending'] }}</li>
        <li>Obstruction: {{ $issueCounts['obstruction'] }}</li>
        <li>Login Issue: {{ $issueCounts['login_issue'] }}</li>
    </ul>

</body>
</html>

 --}}






 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #4A90E2;
            margin-bottom: 20px;
        }
        h2 {
            color: #4A90E2;
            margin-top: 30px;
        }
        .alert {
            padding: 10px;
            background-color: #e7f3fe;
            border: 1px solid #b3d7ff;
            color: #31708f;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4A90E2;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e1f5fe;
        }
        img {
            max-width: 100%;
            height: auto;
            margin: 20px 0;
        }
    </style>
</head>
<body>

    @if(session('message'))
    <div class="alert">{{ session('message') }}</div>
    @endif

    @if(request('client_id'))
    <h2>Report for Client: {{ $client_name }}</h2>
@else
    <h2>Report for All Clients</h2>
@endif

    <h1>Report Results</h1>

    <table>
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
                <td>{{ $entry->poor_cable ? 'Yes' : 'No' }}</td>
                <td>{{ $entry->update_pending ? 'Yes' : 'No' }}</td>
                <td>{{ $entry->obstruction ? 'Yes' : 'No' }}</td>
                <td>{{ $entry->login_issue ? 'Yes' : 'No' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Issue Counts</h2>
    <ul>
        <li>Poor Cable: {{ $issueCounts['poor_cable'] }}</li>
        <li>Update Pending: {{ $issueCounts['update_pending'] }}</li>
        <li>Obstruction: {{ $issueCounts['obstruction'] }}</li>
        <li>Login Issue: {{ $issueCounts['login_issue'] }}</li>
    </ul>


</body>
</html>
