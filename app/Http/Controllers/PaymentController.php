<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\ActivityLog;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('reservation.client')->orderBy('paid_at', 'desc');
        $reservation = null;
        if ($request->has('reservation_id')) {
            $query->where('reservation_id', $request->reservation_id);
            $reservation = Reservation::with('client','room')->withTrashed()->find($request->reservation_id);
        }

        $payments = $query->paginate(20)->withQueryString();
        return view('payments.index', compact('payments', 'reservation'));
    }

    public function create(Request $request)
    {
        $reservation = null;
        if ($request->has('reservation_id')) {
            $reservation = Reservation::with('client','room')->find($request->reservation_id);
        }
        return view('payments.create', compact('reservation'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'amount' => 'required|numeric|min:0',
            // disallow a zero payment, must be at least centime
            'paid_amount' => 'required|numeric|min:0.01',
            'method' => 'required|string|max:100',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'card_number' => 'nullable|string|max:32',
            'mobile_number' => 'nullable|string|max:32',
        ]);

        // if method is card or mobile_money, ensure appropriate number present
        if ($data['method'] === 'card' && empty($data['card_number'])) {
            return back()->withErrors(['card_number' => 'Le numéro de carte est requis pour les paiements par carte.'])->withInput();
        }
        if ($data['method'] === 'mobile_money' && empty($data['mobile_number'])) {
            return back()->withErrors(['mobile_number' => 'Le numéro mobile est requis pour les paiements mobile money.'])->withInput();
        }

        $reservation = Reservation::findOrFail($data['reservation_id']);
        // prevent additional payments if the reservation is already fully paid
        $totalPaidBefore = Payment::where('reservation_id', $reservation->id)
            ->whereNull('deleted_at')
            ->sum('paid_amount');
        if ($totalPaidBefore >= $reservation->total_price) {
            return back()
                ->withErrors(['paid_amount' => 'Cette réservation est déjà réglée.'])
                ->withInput();
        }

        // require the amount paid to be at least the requested amount (no underpayment)
        if ($data['paid_amount'] < $data['amount']) {
            return back()
                ->withErrors(['paid_amount' => 'Le montant payé ne peut pas être inférieur au montant demandé.'])
                ->withInput();
        }

        $payment = Payment::create([
            'reservation_id' => $data['reservation_id'],
            'amount' => $data['amount'],
            'paid_amount' => $data['paid_amount'],
            'status' => $data['paid_amount'] >= $data['amount'] ? 'paid' : 'partial',
            'method' => $data['method'],
            'reference_number' => $data['reference_number'] ?? null,
            'paid_at' => Carbon::now(),
            'notes' => $data['notes'] ?? null,
            'card_number' => $data['card_number'] ?? null,
            'mobile_number' => $data['mobile_number'] ?? null,
        ]);

        // Recalculate total paid for reservation and update status
        $totalPaid = Payment::where('reservation_id', $reservation->id)->sum('paid_amount');
        if ($totalPaid >= $reservation->total_price) {
            $reservation->status = 'paid';
        } elseif ($totalPaid > 0) {
            $reservation->status = 'partial';
        }
        $reservation->save();

        // Log payment creation
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Création',
            'description' => "Paiement #{$payment->id} - Réservation #{$reservation->id} - {$data['paid_amount']} MGA",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('payments.index')->with('success', 'Paiement enregistré avec succès.');
    }

    public function show($id)
    {
        $payment = Payment::with('reservation.client','reservation.room')->findOrFail($id);
        return view('payments.show', compact('payment'));
    }

    public function destroy($id)
    {
        // always fetch including trashed so we can know if it was previously cancelled
        $payment = Payment::withTrashed()->findOrFail($id);
        $paymentId = $payment->id;
        $reservationId = $payment->reservation_id;

        if ($payment->trashed()) {
            // already cancelled; nothing further to do (no permanent deletion)
            return redirect()->back()->with('success', 'Ce paiement a déjà été annulé.');
        }

        // soft delete the payment
        $payment->delete();
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Annulation',
            'description' => "Paiement #{$paymentId} annulé",
            'ip_address' => request()->ip(),
        ]);

        // when a payment is cancelled, mark the reservation cancelled too
        $reservation = Reservation::find($reservationId);
        if ($reservation && $reservation->status !== 'cancelled') {
            $reservation->status = 'cancelled';
            $reservation->save();
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Annulation',
                'description' => "Réservation #{$reservationId} annulée suite à paiement annulé",
                'ip_address' => request()->ip(),
            ]);
        }

        return redirect()->route('payments.history', ['reservation_id' => $reservationId])->with('success', 'Paiement supprimé.');
    }

    /**
     * Display a history of payments including soft-deleted (cancelled/refunded).
     * Shows all payments, including trashed ones, for audit trail visibility.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function history(Request $request)
    {
        $query = Payment::withTrashed()->with('reservation.client')->orderBy('paid_at', 'desc');
        $reservation = null;
        if ($request->has('reservation_id')) {
            $query->where('reservation_id', $request->reservation_id);
            $reservation = Reservation::with('client','room')->withTrashed()->find($request->reservation_id);
        }

        $payments = $query->paginate(20)->withQueryString();
        return view('payments.history', compact('payments', 'reservation'));
    }
}
