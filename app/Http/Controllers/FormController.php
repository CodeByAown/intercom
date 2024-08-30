<?php

namespace App\Http\Controllers;

use App\Models\client;
use App\Models\Entry;
use App\Models\Kit;
use App\Models\Site;
use App\Models\Ticket;
use Illuminate\Http\Request;

class FormController extends Controller
{

    public function index()
    {
        $clients = client::all();
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

        // Save the form data to the database
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
        $entry->save();

        // Check if the warning box is triggered and if so, handle tickets
        if ($request->speed !== '1gbps' || $request->poor_cable !== '0' || $request->update_pending !== '0' || $request->obstruction !== '0' || $request->login_issue !== '0') {
            // Check if a ticket is already open or needs to be opened (this logic can be expanded based on your specific requirements)
            if (!$this->ticketExists($entry)) {
                $this->openNewTicket($entry);
            }
        }

        // Clear form fields and redirect back with a success message
        return redirect()->back()->with('status', 'Form saved successfully!');
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
        $ticket->location = 'Unknown'; // You might want to adjust this based on your data
        $ticket->wan = 'Unknown'; // You might want to adjust this based on your data
        $ticket->reason = 'Issue detected'; // Customize the reason as per your requirements
        $ticket->status = 'active';
        $ticket->save();
    }
}
