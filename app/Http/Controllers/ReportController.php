<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Client;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\FontMetrics;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class ReportController extends Controller
{
    public function index()
    {
        $clients = Client::all(); // Assuming you have a Client model
        return view('admin.reports.index', compact('clients'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'time_period' => 'required',
            'client_id' => 'nullable',
        ]);

        $startDate = null;
        $endDate = now();

        switch ($request->time_period) {
            case 'last_week':
                $startDate = now()->subWeek();
                break;
            case 'last_month':
                $startDate = now()->subMonth();
                break;
            case 'last_12_months':
                $startDate = now()->subYear();
                break;
        }

        // Retrieve entries based on the date range and client selection
        $query = Entry::whereBetween('date', [$startDate, $endDate]);

        // if ($request->client_id) {
        //     $query->where('client_id', $request->client_id);
        // }

        if ($request->client_id) {
            $query->where('client_id', $request->client_id);
            $client = Client::find($request->client_id);
            $client_name = $client ? $client->name : 'Unknown Client';
        } else {
            $client_name = null; // For all clients
        }

        $entries = $query->get();
        if ($entries->isEmpty()) {
            return redirect()->back()->with('message', 'No entries found for this time period.');
        }

        // Prepare data for the pie chart
        $issueCounts = $this->getIssueCounts($entries);

        return view('admin.reports.result', compact('entries', 'issueCounts','client_name'));
    }


    private function getIssueCounts($entries)
    {
        $issueCounts = [
            'poor_cable' => 0,
            'update_pending' => 0,
            'obstruction' => 0,
            'login_issue' => 0,
        ];

        foreach ($entries as $entry) {
            if ($entry->poor_cable) $issueCounts['poor_cable']++;
            if ($entry->update_pending) $issueCounts['update_pending']++;
            if ($entry->obstruction) $issueCounts['obstruction']++;
            if ($entry->login_issue) $issueCounts['login_issue']++;
        }

        return $issueCounts;
    }


    // public function export(Request $request)
    // {
    //     $request->validate([
    //         'time_period' => 'required|string',
    //     ]);

    //     // Similar logic to generate report data
    //     $startDate = null;
    //     $endDate = now();

    //     switch ($request->time_period) {
    //         case 'last_week':
    //             $startDate = now()->subWeek();
    //             break;
    //         case 'last_month':
    //             $startDate = now()->subMonth();
    //             break;
    //         case 'last_12_months':
    //             $startDate = now()->subYear();
    //             break;
    //     }

    //     $entries = Entry::whereBetween('date', [$startDate, $endDate])->get();
    //     if ($entries->isEmpty()) {
    //         return redirect()->back()->with('message', 'No entries found for this time period.');
    //     }

    //     $issueCounts = $this->getIssueCounts($entries);

    //     // Load the view for PDF
    //     $pdf = pdf::loadView('admin.reports.pdf', compact('entries', 'issueCounts'));
    //     return $pdf->download('report.pdf');
    // }




    public function export(Request $request)
    {
        $request->validate([
            'time_period' => 'required',
            'client_id' => 'nullable',
        ]);

        $startDate = null;
        $endDate = now();

        switch ($request->time_period) {
            case 'last_week':
                $startDate = now()->subWeek();
                break;
            case 'last_month':
                $startDate = now()->subMonth();
                break;
            case 'last_12_months':
                $startDate = now()->subYear();
                break;
        }

        $query = Entry::whereBetween('date', [$startDate, $endDate]);

        if ($request->client_id) {
            $query->where('client_id', $request->client_id);
            $client = Client::find($request->client_id);
            $client_name = $client ? $client->name : 'Unknown Client';
        } else {
            $client_name = null; // For all clients
        }

        $entries = $query->get();
        if ($entries->isEmpty()) {
            return redirect()->back()->with('message', 'No entries found for this time period.');
        }

        $issueCounts = $this->getIssueCounts($entries);

        // Generate charts as images
        $this->generateCharts($issueCounts);

        // Load the view for PDF
        $pdf = Pdf::loadView('admin.reports.pdf', compact('entries', 'issueCounts', 'client_name'));
        return $pdf->download('report.pdf');
    }




    // extras

    private function generateCharts($issueCounts)
{
    // Generate Pie Chart
    $pieChartPath = 'charts/pie_chart.png';
    $this->createChartImage('pie', $issueCounts, $pieChartPath);

    // Generate Bar Chart
    $barChartPath = 'charts/bar_chart.png';
    $this->createChartImage('bar', $issueCounts, $barChartPath);

    // Generate Line Chart
    $lineChartPath = 'charts/line_chart.png';
    $this->createChartImage('line', $issueCounts, $lineChartPath);
}

private function createChartImage($type, $data, $path)
{
    $chartData = json_encode($data);
    $chartJs = "
        <canvas id='chart' width='400' height='400'></canvas>
        <script>
            const ctx = document.getElementById('chart').getContext('2d');
            const chartData = $chartData;
            const config = {
                type: '$type',
                data: {
                    labels: ['Poor Cable', 'Update Pending', 'Obstruction', 'Login Issue'],
                    datasets: [{
                        label: 'Issue Frequency',
                        data: [chartData.poor_cable, chartData.update_pending, chartData.obstruction, chartData.login_issue],
                        backgroundColor: [
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(75, 192, 192, 0.6)'
                        ],
                    }]
                }
            };
            const myChart = new Chart(ctx, config);
            const img = ctx.canvas.toDataURL('image/png');
            img.onload = function() {
                var imgElement = document.createElement('img');
                imgElement.src = img;
                imgElement.width = 400; // Adjust width as needed
                imgElement.height = 400; // Adjust height as needed
                document.body.appendChild(imgElement);
                var link = document.createElement('a');
                link.href = img;
                link.download = '$path';
                link.click();
            };
        </script>
    ";

    // Save the chart image
    Storage::put($path, $chartJs);
}

}

