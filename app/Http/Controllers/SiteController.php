<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
      // Display a listing of the sites.
      public function index()
      {
          $sites = Site::with('client')->get();
          return view('admin.sites.index', compact('sites'));
      }

      // Show the form for creating a new site.
      public function create()
      {
          $clients = Client::all();
          return view('admin.sites.create', compact('clients'));
      }

      // Store a newly created site in storage.
      public function store(Request $request)
      {
          $request->validate([
              'client_id' => 'required',
              'name' => 'required',
          ]);

          Site::create($request->all());

          return redirect()->route('sites.index')->with('status', 'Site created successfully!');
      }

      // Display the specified site.
      public function show(Site $site)
      {
          return view('admin.sites.show', compact('site'));
      }

      // Show the form for editing the specified site.
      public function edit(Site $site)
      {
          $clients = Client::all();
          return view('admin.sites.edit', compact('site', 'clients'));
      }

      // Update the specified site in storage.
      public function update(Request $request, Site $site)
      {
          $request->validate([
              'client_id' => 'required',
              'name' => 'required',
          ]);

          $site->update($request->all());

          return redirect()->route('sites.index')->with('status', 'Site updated successfully!');
      }

      // Remove the specified site from storage.
      public function destroy(Site $site)
      {
          $site->delete();

          return redirect()->route('sites.index')->with('status', 'Site deleted successfully!');
      }
}
