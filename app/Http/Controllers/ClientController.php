<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::orderBy('last_name');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%")
                  ->orWhere('email',      'like', "%{$search}%")
                  ->orWhere('phone',      'like', "%{$search}%");
            });
        }

        $clients = $query->paginate(20)->withQueryString();

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
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:clients,email',
            'phone'      => ['nullable','regex:/^\+261\s3\d\s\d{2}\s\d{3}\s\d{2}$/'],
            'address'    => 'nullable|string',
        ]);

        $client = Client::create($data);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'Création',
            'description' => "Client #{$client->id} - {$client->first_name} {$client->last_name}",
            'ip_address'  => request()->ip(),
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
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:clients,email,' . $client->id,
            'phone'      => ['nullable','regex:/^\+261\s3\d\s\d{2}\s\d{3}\s\d{2}$/'],
            'address'    => 'nullable|string',
        ]);

        $client->update($data);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'Mise à jour',
            'description' => "Client #{$client->id} mise à jour",
            'ip_address'  => request()->ip(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client mis à jour.');
    }

    public function destroy(Client $client)
    {
        $clientName = $client->first_name . ' ' . $client->last_name;
        $client->delete();

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'Suppression',
            'description' => "Client #{$client->id} ({$clientName}) supprimé",
            'ip_address'  => request()->ip(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client supprimé.');
    }

    /**
     * API — réservations d'un client (pour le modal)
     */
    public function reservations(Client $client)
    {
        $statusLabels = [
            'confirmed'   => 'Confirmée',
            'pending'     => 'En attente',
            'checked_in'  => 'Checked in',
            'checked_out' => 'Checked out',
            'cancelled'   => 'Annulée',
            'paid'        => 'Payée',
            'partial'     => 'Partiel',
        ];

        $reservations = $client->reservations()
            ->with('room')
            ->orderByDesc('check_in')
            ->get()
            ->map(function ($res) use ($statusLabels) {
                return [
                    'id'          => $res->id,
                    'room_number' => $res->room->room_number ?? '—',
                    'room_type'   => ucfirst($res->room->type ?? ''),
                    'check_in'    => \Carbon\Carbon::parse($res->check_in)->format('d/m/Y'),
                    'check_out'   => \Carbon\Carbon::parse($res->check_out)->format('d/m/Y'),
                    'status'      => $res->status,
                    'status_label'=> $statusLabels[$res->status] ?? ucfirst($res->status),
                    'total_price' => number_format($res->total_price, 0, ',', ' '),
                ];
            });

        return response()->json($reservations);
    }

    /**
     * API — recherche Select2
     */
    public function search(Request $request)
    {
        $term = $request->get('q', '');

        $clients = Client::where(function ($query) use ($term) {
            $query->where('first_name', 'LIKE', "%{$term}%")
                  ->orWhere('last_name',  'LIKE', "%{$term}%")
                  ->orWhere('email',      'LIKE', "%{$term}%")
                  ->orWhere('phone',      'LIKE', "%{$term}%");
        })
        ->orderBy('last_name')
        ->limit(20)
        ->get(['id', 'first_name', 'last_name', 'email', 'phone']);

        return response()->json([
            'results' => $clients->map(fn($c) => [
                'id'   => $c->id,
                'text' => "{$c->first_name} {$c->last_name} - {$c->email}",
            ])
        ]);
    }
}