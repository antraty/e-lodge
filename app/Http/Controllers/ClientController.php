<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('last_name')->paginate(20);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => ['nullable','regex:/^\+261\s3\d\s\d{2}\s\d{3}\s\d{2}$/'],
            'address' => 'nullable|string',
        ]);

        $client = Client::create($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Création',
            'description' => "Client #{$client->id} - {$client->first_name} {$client->last_name}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client ajouté.');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => ['nullable','regex:/^\+261\s3\d\s\d{2}\s\d{3}\s\d{2}$/'],
            'address' => 'nullable|string',
        ]);

        $client->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Mise à jour',
            'description' => "Client #{$client->id} mise à jour",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client mis à jour.');
    }

    public function destroy(Client $client)
    {
        $clientName = $client->first_name . ' ' . $client->last_name;
        $client->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Suppression',
            'description' => "Client #{$client->id} ({$clientName}) supprimé",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client supprimé.');
    }
}
