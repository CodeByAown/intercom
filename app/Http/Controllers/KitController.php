<?php

namespace App\Http\Controllers;

use App\Models\Kit;
use App\Models\Site;
use Illuminate\Http\Request;

class KitController extends Controller
{
    // Display a listing of the kits.
    public function index()
    {
        $kits = Kit::with('site.client')->get();
        return view('admin.kits.index', compact('kits'));
    }

    // Show the form for creating a new kit.
    public function create()
    {
        $sites = Site::all();
        $uniqueKitNumber = 'KIT-' . date('Ymd') . '-' . strtoupper(uniqid());
        return view('admin.kits.create', compact('sites','uniqueKitNumber'));
    }

    // Store a newly created kit in storage.
    public function store(Request $request)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'kit_number' => 'required',
        ]);

        Kit::create($request->all());

        return redirect()->route('kits.index')->with('status', 'Kit created successfully!');
    }

    // Display the specified kit.
    public function show(Kit $kit)
    {
        return view('admin.kits.show', compact('kit'));
    }

    // Show the form for editing the specified kit.
    public function edit(Kit $kit)
    {
        $sites = Site::all();
        return view('admin.kits.edit', compact('kit', 'sites'));
    }

    // Update the specified kit in storage.
    public function update(Request $request, Kit $kit)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'kit_number' => 'required|string|max:255|unique:kits,kit_number,' . $kit->id,
        ]);

        $kit->update($request->all());

        return redirect()->route('kits.index')->with('status', 'Kit updated successfully!');
    }

    // Remove the specified kit from storage.
    public function destroy(Kit $kit)
    {
        $kit->delete();

        return redirect()->route('kits.index')->with('status', 'Kit deleted successfully!');
    }



}
