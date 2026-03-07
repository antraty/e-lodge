<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\Client;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF as DomPDFFacade;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['client','room'])->orderBy('check_in','desc')->paginate(20);
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show trashed (deleted) reservations for history.
     */
    public function history()
    {
        // Include both soft-deleted and those merely cancelled (status = cancelled)
        $reservations = Reservation::withTrashed()
            ->with(['client','room'])
            ->where('status', 'cancelled')
            ->orWhereNotNull('deleted_at')
            ->orderByDesc('deleted_at')
            ->paginate(20);
        return view('reservations.history', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('client','room');
        return view('reservations.show', compact('reservation'));
    }

    public function create()
    {
        $clients = Client::orderBy('last_name')->get();
        $rooms = Room::available()->get();
        return view('reservations.create', compact('clients','rooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'room_id' => 'required|exists:rooms,id',
            // don't allow back‑dating: arrival must be today or later
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
        ]);

        // Vérifier disponibilité
        $conflict = Reservation::where('room_id', $data['room_id'])
            ->where(function($q) use ($data){
                $q->where('check_in', '<', $data['check_out'])->where('check_out','>', $data['check_in']);
            })->whereNotIn('status',['cancelled','checked_out'])->exists();

        if ($conflict) {
            return back()->withErrors(['room_id' => 'La chambre n\'est pas disponible pour ces dates.'])->withInput();
        }

        // Calculer total
        $room = Room::findOrFail($data['room_id']);
        $nights = (new \DateTime($data['check_out']))->diff(new \DateTime($data['check_in']))->days;
        $total = $nights * $room->price_per_night;

        $reservation = Reservation::create(array_merge($data, [
            'status' => 'confirmed',
            'total_price' => $total,
        ]));

        // Log the creation with details
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Création',
            'description' => "Réservation #{$reservation->id} - {$reservation->client->first_name} {$reservation->client->last_name}",
            'ip_address' => request()->ip(),
        ]);

        // Update room status if reservation is active today
        $this->updateRoomStatus($reservation->room_id);

        return redirect()->route('reservations.index')->with('success','Réservation créée.');
    }

    public function edit(Reservation $reservation)
    {
        $clients = Client::orderBy('last_name')->get();
        $rooms = Room::orderBy('room_number')->get();
        return view('reservations.edit', compact('reservation','clients','rooms'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled,paid,partial',
            'special_requests' => 'nullable|string',
        ]);

        // Vérifier disponibilité si room or dates changed
        $conflict = Reservation::where('room_id', $data['room_id'])
            ->where('id','!=',$reservation->id)
            ->where(function($q) use ($data){
                $q->where('check_in', '<', $data['check_out'])->where('check_out','>', $data['check_in']);
            })->whereNotIn('status',['cancelled','checked_out'])->exists();

        if ($conflict) {
            return back()->withErrors(['room_id' => 'La chambre n\'est pas disponible pour ces dates.'])->withInput();
        }

        $room = Room::findOrFail($data['room_id']);
        $nights = (new \DateTime($data['check_out']))->diff(new \DateTime($data['check_in']))->days;
        $total = $nights * $room->price_per_night;

        $oldRoomId = $reservation->room_id;

        $reservation->update(array_merge($data, ['total_price' => $total]));

        // Log the update with details
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Mise à jour',
            'description' => "Réservation #{$reservation->id} mise à jour",
            'ip_address' => request()->ip(),
        ]);

        // If room changed or dates/status changed, refresh statuses for old and new rooms
        if ($oldRoomId != $reservation->room_id) {
            $this->updateRoomStatus($oldRoomId);
        }
        $this->updateRoomStatus($reservation->room_id);

        return redirect()->route('reservations.index')->with('success','Réservation mise à jour.');
    }

    public function destroy(Request $request, Reservation $reservation)
    {
        // if reservation is not yet cancelled, mark it cancelled instead
        if ($reservation->status !== 'cancelled') {
            $reservation->update(['status' => 'cancelled']);
            $this->updateRoomStatus($reservation->room_id);

            // log cancellation explicitly
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Annulation',
                'description' => "Réservation #{$reservation->id} annulée",
                'ip_address' => $request->ip(),
            ]);

            // prevent middleware from logging this DELETE request as a suppression
            $request->attributes->set('skip_log', true);

            return redirect()->route('reservations.index')->with('success', 'Réservation annulée. Appuyez à nouveau pour supprimer définitivement.');
        }

        // already cancelled: perform soft delete
        $reservation->delete();
        $this->updateRoomStatus($reservation->room_id);

        // log soft deletion
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Suppression',
            'description' => "Réservation #{$reservation->id} supprimée définitivement",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('reservations.index')->with('success', 'Réservation supprimée. (historique conservé)');
    }

    /**
     * Update a room's status based on whether there's an active reservation today.
     */
    private function updateRoomStatus($roomId)
    {
        if (empty($roomId)) return;

        $today = date('Y-m-d');

        $active = Reservation::where('room_id', $roomId)
            ->whereNotIn('status', ['cancelled','checked_out'])
            ->where('check_in', '<=', $today)
            ->where('check_out', '>', $today)
            ->exists();

        $room = Room::find($roomId);
        if (!$room) return;

        if ($active) {
            $room->update(['status' => 'occupied']);
        } else {
            // Only set as available if not in maintenance
            if ($room->status !== 'maintenance') {
                $room->update(['status' => 'available']);
            }
        }
    }

    /**
     * Generate invoice (PDF) for a reservation.
     */
    public function invoice(Reservation $reservation)
    {
        $reservation->load('client','room');

        // If DomPDF is available, generate PDF; otherwise return HTML preview
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class) || class_exists(DomPDFFacade::class)) {
            try {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reservations.invoice', compact('reservation'));
                $filename = 'facture_reservation_' . $reservation->id . '.pdf';
                return $pdf->download($filename);
            } catch (\Exception $e) {
                // Fall back to HTML view on error
                return view('reservations.invoice', compact('reservation'))->withErrors(['pdf' => $e->getMessage()]);
            }
        }

        // Library not installed — show HTML invoice and instructions
        return view('reservations.invoice', compact('reservation'));
    }
}
