<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Facture Réservation #{{ $reservation->id }}</title>
    <style>
        body { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; color: #333; }
        .header { display:flex; justify-content:space-between; margin-bottom:20px }
        .logo { font-weight:700; font-size:20px }
        .meta { text-align:right }
        table { width:100%; border-collapse:collapse; margin-top:20px }
        th, td { padding:8px; border:1px solid #ddd }
        .text-right { text-align:right }
        .total { font-weight:700 }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">e-Lodge</div>
        <div class="meta">
            <div>Facture: #{{ $reservation->id }}</div>
            <div>Date: {{ now()->format('Y-m-d') }}</div>
        </div>
    </div>

    <div>
        <strong>Facturer à :</strong>
        <div>{{ $reservation->client->first_name }} {{ $reservation->client->last_name }}</div>
        <div>{{ $reservation->client->email }}</div>
        @if($reservation->client->phone)<div>{{ $reservation->client->phone }}</div>@endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th class="text-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @php
                $nights = \Carbon\Carbon::parse($reservation->check_in)->diffInDays(\Carbon\Carbon::parse($reservation->check_out));
            @endphp
            <tr>
                <td>Chambre #{{ $reservation->room->room_number }} ({{ ucfirst($reservation->room->type) }}) — du {{ $reservation->check_in }} au {{ $reservation->check_out }}</td>
                <td>{{ number_format($reservation->room->price_per_night,2) }} MGA</td>
                <td>{{ $nights }} nuit(s)</td>
                <td class="text-right">{{ number_format($reservation->total_price,2) }} MGA</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right total">Total</td>
                <td class="text-right total">{{ number_format($reservation->total_price,2) }} MGA</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top:20px">
        {{-- first show reservation status so the invoice clearly indicates where the booking stands --}}
        <strong>Statut réservation:</strong> {{ ucfirst($reservation->status) }}
    </div>
    <div style="margin-top:10px">
        @php
            // determine payment totals and last payment for the invoice
            $paymentsQuery = $reservation->payments()->whereNull('deleted_at');
            $totalPaid = $paymentsQuery->sum('paid_amount');
            $lastPayment = $paymentsQuery->orderBy('paid_at','desc')->first();
            if ($lastPayment) {
                $lastPaidAt = optional($lastPayment->paid_at ?? $lastPayment->created_at)->format('Y-m-d H:i:s');
            } else {
                $lastPaidAt = null;
            }
        @endphp
        <strong>Statut paiement:</strong>
        @if($reservation->status === 'paid' || $totalPaid >= $reservation->total_price)
            Payé intégralement
            @if($lastPaidAt)
                le {{ $lastPaidAt }}
            @endif
            @if($lastPayment && $lastPayment->paid_amount > $reservation->total_price)
                <br><small>Rendu : {{ number_format($lastPayment->paid_amount - $reservation->total_price,2) }} MGA</small>
            @endif
        @elseif($totalPaid > 0)
            Partiellement payé ({{ number_format($totalPaid,2) }} / {{ number_format($reservation->total_price,2) }} MGA)
            @if($lastPaidAt)
                <br><small>Dernier paiement : {{ $lastPaidAt }}</small>
            @endif
        @else
            Non payé
        @endif
    </div>

    {{-- if there was at least one payment record, show method and identifier info --}}
    @if($lastPayment)
        <div style="margin-top:10px">
            <strong>Détails du dernier paiement :</strong><br>
            Méthode : {{ ucfirst(str_replace('_',' ',$lastPayment->method)) }}
            @if($lastPayment->method === 'mobile_money' && $lastPayment->mobile_number)
                ({{ $lastPayment->mobile_number }})
            @endif
            @if($lastPayment->method === 'card' && $lastPayment->card_number)
                ({{ $lastPayment->card_number }})
            @endif
        </div>
    @endif

    @if(session('errors'))
        <div style="margin-top:10px;color:red">Erreur PDF: {{ session('errors')->first('pdf') }}</div>
    @endif

    <div style="margin-top:30px;font-size:12px;color:#666">e-Lodge — Administration</div>
</body>
</html>
