<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Client;
use App\Models\Site;
use App\Models\Kit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // Display a listing of the tickets.
    public function index()
    {
        $tickets = Ticket::all(); // Fetch all tickets from the database
        return view('admin.tickets.index', compact('tickets'));
    }

    public function reopen($id)
{
    $ticket = Ticket::findOrFail($id);
    $ticket->status = 'active';
    $ticket->save();

    return redirect()->route('tickets.index')->with('status', 'Ticket activated successfully.');
}


    public function close(Request $request, Ticket $ticket)
    {
        // Update the status of the ticket to 'closed'
        $ticket->status = 'closed';
        $ticket->save();

        return redirect()->route('tickets.index')->with('success', 'Ticket has been closed successfully!');
    }


    // Show the form for creating a new ticket.
    public function create()
    {
        $clients = Client::all();
        $sites = Site::all();
        $kits = Kit::all();
        return view('admin.tickets.create', compact('clients', 'sites', 'kits'));
    }

    // Store a newly created ticket in storage.
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'ticket_number' => 'required',
            'location' => 'required|string',
            'wan' => 'required|string',
            'reason' => 'required|string',
        ]);

        $ticket = new Ticket();
        $ticket->client_id = 1;
        $ticket->site_id = 1;
        $ticket->kit_id = 1;
        $ticket->date = $request->date;
        $ticket->ticket_number = $request->ticket_number;
        $ticket->location = $request->location;
        $ticket->wan = $request->wan;
        $ticket->reason = $request->reason;
        $ticket->status = 'active'; // New tickets start as active
        $ticket->save();

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully!');
    }


    // Display the specified ticket.
    public function show(Ticket $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }

    // Show the form for editing the specified ticket.
    public function edit(Ticket $ticket)
    {
        $clients = Client::all();
        $sites = Site::all();
        $kits = Kit::all();
        return view('admin.tickets.edit', compact('ticket', 'clients', 'sites', 'kits'));
    }

    // Update the specified ticket in storage.
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'site_id' => 'required|exists:sites,id',
            'kit_id' => 'required|exists:kits,id',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'wan' => 'required|string|max:255',
            'reason' => 'required|string|max:255',
            'status' => 'required|string|in:active,closed',
        ]);

        $ticket->update($request->all());

        return redirect()->route('tickets.index')->with('status', 'Ticket updated successfully!');
    }

    // Remove the specified ticket from storage.
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tickets.index')->with('status', 'Ticket deleted successfully!');
    }


    // public function close(Request $request, Ticket $ticket)
    // {
    //     // Update the status of the ticket to 'closed'
    //     $ticket->status = 'closed';
    //     $ticket->save();

    //     // Redirect back to the tickets index page with a success message
    //     return redirect()->route('tickets.index')->with('success', 'Ticket has been closed successfully.');
    // }


}
