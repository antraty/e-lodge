<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\Client;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\PDF as DomPDFFacade;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['client', 'room'])->orderBy('check_in', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('client', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name',  'like', "%{$search}%");
                })->orWhereHas('room', function ($q2) use ($search) {
                    $q2->where('room_number', 'like', "%{$search}%");
                });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($from = $request->input('date_from')) {
            $query->where('check_in', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->where('check_in', '<=', $to);
        }

        $reservations = $query->paginate(20)->withQueryString();

        return view('reservations.index', compact('reservations'));
    }

    public function history()
    {
        $reservations = Reservation::withTrashed()
            ->with(['client', 'room'])
            ->where('status', 'cancelled')
            ->orWhereNotNull('deleted_at')
            ->orderByDesc('deleted_at')
            ->paginate(20);

        return view('reservations.history', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('client', 'room');
        return view('reservations.show', compact('reservation'));
    }

    public function create()
    {
        $clients = Client::orderBy('last_name')->get();
        $rooms   = Room::whereIn('status', ['available', 'reserved'])
                       ->orderBy('room_number')
                       ->get();
        return view('reservations.create', compact('clients', 'rooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'        => 'required|exists:clients,id',
            'room_id'          => 'required|exists:rooms,id',
            'check_in'         => 'required|date|after_or_equal:today',
            'check_out'        => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
        ]);

        // Vérifier conflit de dates
        $conflict = Reservation::where('room_id', $data['room_id'])
            ->where(function ($q) use ($data) {
                $q->where('check_in', '<', $data['check_out'])
                  ->where('check_out', '>', $data['check_in']);
            })
            ->whereNotIn('status', ['cancelled', 'checked_out'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['room_id' => 'La chambre n\'est pas disponible pour ces dates.'])->withInput();
        }

        $room   = Room::findOrFail($data['room_id']);
        $nights = (new \DateTime($data['check_out']))->diff(new \DateTime($data['check_in']))->days;
        $total  = $nights * $room->price_per_night;

        $reservation = Reservation::create(array_merge($data, [
            'status'      => 'confirmed',
            'total_price' => $total,
        ]));

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Création',
            'description' => "Réservation #{$reservation->id} - {$reservation->client->first_name} {$reservation->client->last_name}",
            'ip_address'  => request()->ip(),
        ]);

        // Synchroniser le statut de la chambre
        $room->syncStatus();

        return redirect()->route('reservations.index')->with('success', 'Réservation créée.');
    }

    public function edit(Reservation $reservation)
    {
        $clients = Client::orderBy('last_name')->get();
        $rooms   = Room::orderBy('room_number')->get();
        return view('reservations.edit', compact('reservation', 'clients', 'rooms'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'client_id'        => 'required|exists:clients,id',
            'room_id'          => 'required|exists:rooms,id',
            'check_in'         => 'required|date|after_or_equal:today',
            'check_out'        => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1',
            'status'           => 'required|in:pending,confirmed,checked_in,checked_out,cancelled,paid,partial',
            'special_requests' => 'nullable|string',
        ]);

        $conflict = Reservation::where('room_id', $data['room_id'])
            ->where('id', '!=', $reservation->id)
            ->where(function ($q) use ($data) {
                $q->where('check_in', '<', $data['check_out'])
                  ->where('check_out', '>', $data['check_in']);
            })
            ->whereNotIn('status', ['cancelled', 'checked_out'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['room_id' => 'La chambre n\'est pas disponible pour ces dates.'])->withInput();
        }

        $room      = Room::findOrFail($data['room_id']);
        $nights    = (new \DateTime($data['check_out']))->diff(new \DateTime($data['check_in']))->days;
        $total     = $nights * $room->price_per_night;
        $oldRoomId = $reservation->room_id;

        $reservation->update(array_merge($data, ['total_price' => $total]));

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Mise à jour',
            'description' => "Réservation #{$reservation->id} mise à jour",
            'ip_address'  => request()->ip(),
        ]);

        // Resynchroniser l'ancienne chambre si elle a changé
        if ($oldRoomId != $reservation->room_id) {
            $oldRoom = Room::find($oldRoomId);
            $oldRoom?->syncStatus();
        }

        $room->syncStatus();

        return redirect()->route('reservations.index')->with('success', 'Réservation mise à jour.');
    }

    public function destroy(Request $request, Reservation $reservation)
    {
        $room = Room::find($reservation->room_id);

        if ($reservation->status !== 'cancelled') {
            $reservation->update(['status' => 'cancelled']);

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'action'      => 'Annulation',
                'description' => "Réservation #{$reservation->id} annulée",
                'ip_address'  => $request->ip(),
            ]);

            $request->attributes->set('skip_log', true);
            $room?->syncStatus();

            return redirect()->route('reservations.index')
                ->with('success', 'Réservation annulée. Appuyez à nouveau pour supprimer définitivement.');
        }

        $reservation->delete();

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'Suppression',
            'description' => "Réservation #{$reservation->id} supprimée définitivement",
            'ip_address'  => $request->ip(),
        ]);

        $room?->syncStatus();

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation supprimée. (historique conservé)');
    }

    public function invoice(Reservation $reservation)
    {
        $reservation->load('client', 'room');

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class) || class_exists(DomPDFFacade::class)) {
            try {
                $pdf      = \Barryvdh\DomPDF\Facade\Pdf::loadView('reservations.invoice', compact('reservation'));
                $filename = 'facture_reservation_' . $reservation->id . '.pdf';
                return $pdf->download($filename);
            } catch (\Exception $e) {
                return view('reservations.invoice', compact('reservation'))->with('error', $e->getMessage());
            }
        }

        return view('reservations.invoice', compact('reservation'));
    }
}