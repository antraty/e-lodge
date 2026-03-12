@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Réservation #{{ $reservation->id }}</h1>

        <div class="mb-3">
        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Retour</a>
        <a href="{{ route('payments.create', ['reservation_id' => $reservation->id]) }}" class="btn btn-success">Enregistrer un paiement</a>
        <a href="{{ route('reservations.invoice', $reservation) }}" class="btn btn-warning" target="_blank">Télécharger la facture (PDF)</a>
        <a href="{{ route('reservations.edit', $reservation) }}" class="btn btn-primary">Modifier</a>
    </div>

    <table class="table table-bordered">
        <tr><th>Client</th><td>{{ $reservation->client->first_name }} {{ $reservation->client->last_name }}</td></tr>
        <tr><th>Chambre</th><td>{{ $reservation->room->room_number }} ({{ ucfirst($reservation->room->type) }})</td></tr>
        <tr><th>Arrivée</th><td>{{ $reservation->check_in }}</td></tr>
        <tr><th>Départ</th><td>{{ $reservation->check_out }}</td></tr>
        <tr><th>Nombre de personnes</th><td>{{ $reservation->number_of_guests }}</td></tr>
        <tr><th>Statut de réservation</th><td>
            @php
                // human-friendly labels for reservation statuses
                $statusLabels = [
                    'cancelled'   => 'Annulée',
                    'checked_out' => 'Départ effectué',
                    'checked_in'  => 'Arrivée effectuée',
                    'confirmed'   => 'Confirmée',
                    'paid'        => 'Payée',
                    'partial'     => 'Partielle',
                    'pending'     => 'En attente',
                ];
                $label = $statusLabels[$reservation->status] ?? ucfirst($reservation->status);
                // choose a badge colour based on status category
                switch($reservation->status) {
                    case 'cancelled': $badge = 'badge-danger'; break;
                    case 'checked_out': $badge = 'badge-secondary'; break;
                    case 'checked_in': $badge = 'badge-info'; break;
                    case 'paid': $badge = 'badge-success'; break;
                    case 'partial': $badge = 'badge-warning'; break;
                    default: $badge = 'badge-primary'; break;
                }
            @endphp
            <span class="{{ $badge }}">{{ $label }}</span>
        </td></tr>
        <tr><th>Montant total</th><td>{{ number_format($reservation->total_price,2) }} MGA</td></tr>
        <tr><th>Statut paiement</th><td>
            @php
                // gather payments excluding those soft-deleted (annulés)
                $paymentsQuery = $reservation->payments()->whereNull('deleted_at');
                $totalPaid = $paymentsQuery->sum('paid_amount');
                $remaining = $reservation->total_price - $totalPaid;
                $lastPayment = $paymentsQuery->orderBy('paid_at','desc')->first();
                // guard against no payments at all
                if ($lastPayment) {
                    // use paid_at when available, otherwise created_at
                    $lastPaidAt = optional($lastPayment->paid_at ?? $lastPayment->created_at)->format('Y-m-d H:i:s');
                } else {
                    $lastPaidAt = null;
                }
            @endphp
            @if($reservation->status === 'paid' || $totalPaid >= $reservation->total_price)
                <span class="badge badge-success">✓ Payé intégralement</span>
                ({{ number_format($totalPaid, 2) }} MGA)
                @if($lastPaidAt)
                    <br><small class="text-muted">Dernier paiement : {{ $lastPaidAt }}</small>
                @endif
            @elseif($totalPaid > 0)
                <span class="badge badge-warning">Paiement partiel</span>
                ({{ number_format($totalPaid, 2) }}/{{ number_format($reservation->total_price, 2) }} MGA - Reste: {{ number_format($remaining, 2) }} MGA)
                @if($lastPaidAt)
                    <br><small class="text-muted">Dernier paiement : {{ $lastPaidAt }}</small>
                @endif
            @else
                <span class="badge badge-danger">Non payé</span>
            @endif
        </td></tr>
        <tr><th>Remarques</th><td>{{ $reservation->special_requests }}</td></tr>
    </table>
</div>
@endsection
