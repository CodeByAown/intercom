<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Client;
use App\Models\Site;
use App\Models\Kit;
use App\Models\Ticket;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    // Display a listing of the entries.
    public function index()
    {
        $entries = Entry::with('client', 'site', 'kit', 'ticket')->get();
        return view('admin.entries.index', compact('entries'));
    }

    // Show the form for creating a new entry.
    public function create()
    {
        $clients = Client::all();
        $sites = Site::all();
        $kits = Kit::all();
        $tickets = Ticket::all();
        return view('admin.entries.create', compact('clients', 'sites', 'kits', 'tickets'));
    }

    // Store a newly created entry in storage.
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'site_id' => 'required|exists:sites,id',
            'kit_id' => 'required|exists:kits,id',
            'ticket_id' => 'nullable|exists:tickets,id',
            'date' => 'required|date',
            'observation' => 'required|string|max:255',
            'status' => 'required|string|in:open,closed',
        ]);

        Entry::create($request->all());

        return redirect()->route('entries.index')->with('status', 'Entry created successfully!');
    }

    // Display the specified entry.
    public function show(Entry $entry)
    {
        return view('admin.entries.show', compact('entry'));
    }

    // Show the form for editing the specified entry.
    public function edit(Entry $entry)
    {
        $clients = Client::all();
        $sites = Site::all();
        $kits = Kit::all();
        $tickets = Ticket::all();
        return view('admin.entries.edit', compact('entry', 'clients', 'sites', 'kits', 'tickets'));
    }

    // Update the specified entry in storage.
    public function update(Request $request, Entry $entry)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'site_id' => 'required|exists:sites,id',
            'kit_id' => 'required|exists:kits,id',
            'ticket_id' => 'nullable|exists:tickets,id',
            'date' => 'required|date',
            'observation' => 'required|string|max:255',
            'status' => 'required|string|in:open,closed',
        ]);

        $entry->update($request->all());

        return redirect()->route('entries.index')->with('status', 'Entry updated successfully!');
    }

    // Remove the specified entry from storage.
    public function destroy(Entry $entry)
    {
        $entry->delete();

        return redirect()->route('entries.index')->with('status', 'Entry deleted successfully!');
    }
}
