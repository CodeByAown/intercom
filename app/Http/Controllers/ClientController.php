<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Display a listing of the clients.
    public function index()
    {
        $clients = Client::all();
        return view('admin.clients.index', compact('clients'));
    }

    // Show the form for creating a new client.
    public function create()
    {
        return view('admin.clients.create');
    }

    // Store a newly created client in storage.
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',

        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')->with('status', 'Client created successfully!');
    }

    // Display the specified client.
    public function show(Client $client)
    {
        return view('admin.clients.show', compact('client'));
    }

    // Show the form for editing the specified client.
    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    // Update the specified client in storage.
    public function update(Request $request, Client $client)
    {
        $client->update($request->all());

        return redirect()->route('clients.index')->with('status', 'Client updated successfully!');
    }

    // Remove the specified client from storage.
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')->with('status', 'Client deleted successfully!');
    }
}
