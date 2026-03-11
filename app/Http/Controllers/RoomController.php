<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('room_number')->paginate(15);
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number',
            'type' => 'required|in:single,double,suite,deluxe',
            'capacity' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance',
            'description' => 'nullable|string',
        ]);

        $room = Room::create($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Création',
            'description' => "Chambre #{$room->id} - {$room->room_number} ({$room->type})",
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
            'room_number' => 'required|string|unique:rooms,room_number,' . $room->id,
            'type' => 'required|in:single,double,suite,deluxe',
            'capacity' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance',
            'description' => 'nullable|string',
        ]);

        $room->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Mise à jour',
            'description' => "Chambre #{$room->id} ({$room->room_number}) mise à jour",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('rooms.index')->with('success', 'Chambre mise à jour.');
    }

    public function destroy(Room $room)
    {
        $roomNum = $room->room_number;
        $room->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Suppression',
            'description' => "Chambre #{$room->id} ({$roomNum}) supprimée",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('rooms.index')->with('success', 'Chambre supprimée.');
    }

    /**
     * Vérifier la disponibilité d'une chambre entre deux dates
     */
    public function checkAvailability(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $overlap = Reservation::where('room_id', $data['room_id'])
            ->where(function ($q) use ($data) {
                $q->where('check_in', '<', $data['check_out'])
                  ->where('check_out', '>', $data['check_in']);
            })
            ->whereNotIn('status', ['cancelled', 'checked_out'])
            ->exists();

        return response()->json(['available' => !$overlap]);
    }
}
