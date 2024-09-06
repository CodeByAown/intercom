<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Entry;
use App\Models\Kit;
use App\Models\Site;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FormController extends Controller
{
    /**
     * Show the form for data entry.
     */
    public function index()
    {
        $clients = Client::all(); // Fetch all clients for dropdown
        return view('admin.form', compact('clients'));
    }

    public function showentries()
    {
        $entries = Entry::all(); // Fetch all entries from the database
        return view('admin.showentries', compact('entries'));
    }

    /**
     * Get sites based on the selected client.
     */
    public function getSites(Request $request)
    {
        $sites = Site::where('client_id', $request->client_id)->get();
        $options = '<option value="">Select Site</option>';
        foreach ($sites as $site) {
            $options .= '<option value="'.$site->id.'">'.$site->name.'</option>';
        }
        return $options;
    }

    /**
     * Get kits based on the selected site.
     */
    public function getKits(Request $request)
    {
        $kits = Kit::where('site_id', $request->site_id)->get();
        $options = '<option value="">Select Kit</option>';
        foreach ($kits as $kit) {
            $options .= '<option value="'.$kit->id.'">'.$kit->kit_number.'</option>';
        }
        return $options;
    }

    /**
     * Save the form data and handle ticket creation if needed.
     */
    public function saveForm(Request $request)
    {

        // dd($request->all());

        // Validate the form data
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'site_id' => 'required|exists:sites,id',
            'kit_id' => 'required|exists:kits,id',
            'speed' => 'required|string',
            'poor_cable' => 'required|boolean',
            'update_pending' => 'required|boolean',
            'obstruction' => 'required|boolean',
            'login_issue' => 'required|boolean',
            // New fields validation
            'speed_10mbps' => 'nullable|integer',
            'speed_100mbps' => 'nullable|integer',
            'speed_1gbps' => 'nullable|integer',
            'yes_cablefield' => 'nullable|integer',
            'no_cablefield' => 'nullable|integer',
            'auto_reboot' => 'nullable|integer',
            'manual_reboot' => 'nullable|integer',
            'no_updatepending' => 'nullable|integer',
            'full_obstruction' => 'nullable|integer',
            'partial_obstruction' => 'nullable|integer',
            'no_obstruction' => 'nullable|integer',
            'yes_login_issue' => 'nullable|integer',
            'no_login_issue' => 'nullable|integer',
        ]);

    // Create a new entry
    $entry = new Entry();
    $entry->date = $request->date;
    $entry->client_id = $request->client_id;
    $entry->site_id = $request->site_id;
    $entry->kit_id = $request->kit_id;
    $entry->speed = $request->speed;
    $entry->poor_cable = $request->poor_cable;
    $entry->update_pending = $request->update_pending;
    $entry->obstruction = $request->obstruction;
    $entry->login_issue = $request->login_issue;

    // Save new fields
    $entry->speed_10mbps = $request->input('speed_10mbps');
    $entry->speed_100mbps = $request->input('speed_100mbps');
    $entry->speed_1gbps = $request->input('speed_1gbps');
    $entry->yes_cablefield = $request->input('yes_cablefield');
    $entry->no_cablefield = $request->input('no_cablefield');
    $entry->auto_reboot = $request->input('auto_reboot');
    $entry->manual_reboot = $request->input('manual_reboot');
    $entry->no_updatepending = $request->input('no_updatepending');
    $entry->full_obstruction = $request->input('full_obstruction');
    $entry->partial_obstruction = $request->input('partial_obstruction');
    $entry->no_obstruction = $request->input('no_obstruction');
    $entry->yes_login_issue = $request->input('yes_login_issue');
    $entry->no_login_issue = $request->input('no_login_issue');

    $entry->save();

        // Check if any issues require a ticket
        if ($this->needsTicket($request)) {
            // Check if a ticket already exists; if not, create a new one
            if (!$this->ticketExists($entry)) {
                $this->openNewTicket($entry);
                return redirect()->back()->with('warning', 'Form saved successfully and new ticket has been opened due to detected issues!');
            } else {
                return redirect()->back()->with('warning', 'Form saved successfully and An existing ticket was found for these issues!');
            }
        }

        return redirect()->back()->with('status', 'Form saved successfully!');
    }

    /**
     * Determine if the form data needs a new ticket.
     */
    private function needsTicket($request)
    {
        return !($request->speed === '1Gbps' && !$request->poor_cable && !$request->update_pending && !$request->obstruction && !$request->login_issue);
    }

    /**
     * Check if a ticket exists for the given entry.
     */
    private function ticketExists($entry)
    {
        return Ticket::where('client_id', $entry->client_id)
                     ->where('site_id', $entry->site_id)
                     ->where('kit_id', $entry->kit_id)
                     ->where('status', 'active')
                     ->exists();
    }

    /**
     * Open a new ticket.
     */
    private function openNewTicket($entry)
    {
        $ticket = new Ticket();
        $ticket->date = $entry->date;
        $ticket->client_id = $entry->client_id;
        $ticket->site_id = $entry->site_id;
        $ticket->kit_id = $entry->kit_id;
        $ticket->ticket_number = date('Ymd') . '-' . uniqid();
        $ticket->location = 'Unknown'; // Adjust based on your data
        $ticket->wan = 'Unknown'; // Adjust based on your data
        $ticket->reason = 'Issue detected'; // Customize the reason as needed
        $ticket->status = 'active';
        $ticket->save();
    }
}
