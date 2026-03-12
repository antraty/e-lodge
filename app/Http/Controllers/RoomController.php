<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::orderBy('room_number');

        // Recherche par numéro
        if ($search = $request->input('search')) {
            $query->where('room_number', 'like', '%' . $search . '%');
        }

        // Filtre par type
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        // Filtre par statut
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $rooms = $query->paginate(15)->withQueryString();

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_number'    => 'required|string|unique:rooms,room_number',
            'type'           => 'required|in:single,double,suite,deluxe',
            'capacity'       => 'required|integer|min:1',
            'price_per_night'=> 'required|numeric|min:0',
            'status'         => 'required|in:available,occupied,maintenance',
            'description'    => 'nullable|string',
        ]);

        $room = Room::create($data);

        ActivityLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'Création',
            'description'=> "Chambre #{$room->id} - {$room->room_number} ({$room->type})",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('rooms.index')->with('success', 'Chambre créée avec succès.');
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'room_number'    => 'required|string|unique:rooms,room_number,' . $room->id,
            'type'           => 'required|in:single,double,suite,deluxe',
            'capacity'       => 'required|integer|min:1',
            'price_per_night'=> 'required|numeric|min:0',
            'status'         => 'required|in:available,occupied,maintenance',
            'description'    => 'nullable|string',
        ]);

        $room->update($data);

        ActivityLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'Mise à jour',
            'description'=> "Chambre #{$room->id} ({$room->room_number}) mise à jour",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('rooms.index')->with('success', 'Chambre mise à jour.');
    }

    public function destroy(Room $room)
    {
        $roomNum = $room->room_number;
        $room->delete();

        ActivityLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'Suppression',
            'description'=> "Chambre #{$room->id} ({$roomNum}) supprimée",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('rooms.index')->with('success', 'Chambre supprimée.');
    }
}