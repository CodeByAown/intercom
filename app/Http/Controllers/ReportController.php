<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'time_period' => 'required|string',
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

        // Retrieve entries based on the date range
        $entries = Entry::whereBetween('date', [$startDate, $endDate])->get();

        // Prepare data for the pie chart
        $issueCounts = $this->getIssueCounts($entries);

        return view('admin.reports.result', compact('entries', 'issueCounts'));
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
}
