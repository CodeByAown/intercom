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
        $tickets = Ticket::all();
        return view('admin.tickets.index', compact('tickets'));
    }


    public function activate($id)
    {
        $ticket = Ticket::findOrFail($id);
        if ($ticket->status !== 'active') {
            $ticket->status = 'active';
            $ticket->save();
            return redirect()->route('tickets.index')->with('status', 'Ticket activated successfully.');
        }
        return redirect()->route('tickets.index')->with('warning', 'Ticket is already active.');
    }

    public function close(Request $request, Ticket $ticket)
    {
        $ticket->status = 'closed';
        $ticket->save();
        return redirect()->route('tickets.index')->with('status', 'Ticket has been closed successfully!');
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
            'ticket_number' => 'required|unique:tickets,ticket_number',
            'client_id' => 'required|exists:clients,id',
            'site_id' => 'required|exists:sites,id',
            'kit_id' => 'required|exists:kits,id',
            'location' => 'required|string',
            'wan' => 'required|string',
            'reason' => 'required|string',
        ]);

        $ticket = new Ticket();
        $ticket->client_id = $request->client_id;
        $ticket->site_id = $request->site_id;
        $ticket->kit_id = $request->kit_id;
        $ticket->date = $request->date;
        $ticket->ticket_number = $request->ticket_number;
        $ticket->location = $request->location;
        $ticket->wan = $request->wan;
        $ticket->reason = $request->reason;
        $ticket->status = 'active';
        $ticket->save();

        return redirect()->route('tickets.index')->with('status', 'Ticket created successfully!');
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
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);

        // $request->validate([
        //     'date' => 'required|date',
        //     'ticket_number' => 'required|unique:tickets,ticket_number,' . $ticket->id,
        //     'client_id' => 'required|exists:clients,id',
        //     'site_id' => 'required|exists:sites,id',
        //     'kit_id' => 'required|exists:kits,id',
        //     'location' => 'required|string',
        //     'wan' => 'required|string',
        //     'reason' => 'required|string',
        //     'status' => 'required|string',
        // ]);

        $ticket->update($request->all());

        return redirect()->route('tickets.index')->with('status', 'Ticket updated successfully!');
    }

    // Remove the specified ticket from storage.
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('status', 'Ticket deleted successfully!');
    }
}
