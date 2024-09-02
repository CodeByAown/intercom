<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Entry;
use App\Models\Kit;
use App\Models\Site;
use App\Models\Ticket;
use Illuminate\Http\Request;

class FormController extends Controller
{

    public function index()
    {
        $clients = Client::all();
        return view('admin.form', compact('clients'));
    }

    public function getSites(Request $request)
    {
        $sites = site::where('client_id', $request->client_id)->get();
        $options = '<option value="">Select Site</option>';
        foreach ($sites as $site) {
            $options .= '<option value="'.$site->id.'">'.$site->name.'</option>';
        }
        return $options;
    }
    // next

    public function getKits(Request $request)
    {
        $kits = Kit::where('site_id', $request->site_id)->get();
        $options = '<option value="">Select Kit</option>';
        foreach ($kits as $kit) {
            $options .= '<option value="'.$kit->id.'">'.$kit->kit_number.'</option>';
        }
        return $options;
    }

    // next

    public function saveForm(Request $request)
    {
        // Validate the form data
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'site_id' => 'required|exists:sites,id',
            'kit_id' => 'required|exists:kits,id',
            'speed' => 'required|string|max:255',
            'poor_cable' => 'required|boolean',
            'update_pending' => 'required|boolean',
            'obstruction' => 'required|boolean',
            'login_issue' => 'required|boolean',
        ]);

        // Set the current date automatically
        $entry = new Entry();
        $entry->date = now(); // Automatically set to current date
        $entry->client_id = $request->client_id;
        $entry->site_id = $request->site_id;
        $entry->kit_id = $request->kit_id;
        $entry->speed = $request->speed;
        $entry->poor_cable = $request->poor_cable;
        $entry->update_pending = $request->update_pending;
        $entry->obstruction = $request->obstruction;
        $entry->login_issue = $request->login_issue;
        $entry->save();

        // Check if any issues require a ticket
        if ($this->needsTicket($request)) {
            $this->openNewTicket($entry);
            return redirect()->back()->with('warning', 'A new ticket has been opened due to detected issues.');
        }

        return redirect()->back()->with('status', 'Form saved successfully!');
    }

    private function needsTicket($request)
    {
        return !($request->speed === '1gbps' && !$request->poor_cable && !$request->update_pending && !$request->obstruction && !$request->login_issue);
    }

    // Helper method to check if a ticket exists for the given entry
    private function ticketExists($entry)
    {
        return Ticket::where('client_id', $entry->client_id)
                     ->where('site_id', $entry->site_id)
                     ->where('kit_id', $entry->kit_id)
                     ->where('status', 'active')
                     ->exists();
    }

    // Helper method to open a new ticket
    private function openNewTicket($entry)
    {

        $ticket = new Ticket();
        $ticket->date = $entry->date;
        $ticket->client_id = $entry->client_id;
        $ticket->site_id = $entry->site_id;
        $ticket->kit_id = $entry->kit_id;
        $ticket->ticket_number = date('Ymd') . '-' . strtoupper(uniqid());
        $ticket->location = 'Unknown'; // You might want to adjust this based on your data
        $ticket->wan = 'Unknown'; // You might want to adjust this based on your data
        $ticket->reason = 'Issue detected'; // Customize the reason as per your requirements
        $ticket->status = 'active';
        $ticket->save();
    }
}
