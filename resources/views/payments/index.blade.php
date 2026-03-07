@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <div>
            <h3>Paiements @if(isset($reservation)) pour la réservation #{{ optional($reservation)->id }}@endif</h3>
            @if(isset($reservation))<div>Client: {{ optional($reservation->client)->first_name }} {{ optional($reservation->client)->last_name }}</div>@endif
        </div>
        <div>
            <a href="{{ route('payments.create', isset($reservation) ? ['reservation_id' => $reservation->id] : []) }}" class="btn btn-primary">Enregistrer un paiement</a>
            <a href="{{ route('payments.history', isset($reservation) ? ['reservation_id' => $reservation->id] : []) }}" class="btn btn-secondary ml-2">Voir l'historique</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Réservation</th>
                <th>Client</th>
                <th>Montant payé</th>
                <th>Méthode</th>
                <th>Informations</th>
                <th>Date & heure</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>#{{ optional($p->reservation)->id }} - {{ optional(optional($p->reservation)->room)->room_number ?? 'N/A' }}</td>
                    <td>{{ optional(optional($p->reservation)->client)->first_name ?? '' }} {{ optional(optional($p->reservation)->client)->last_name ?? '' }}</td>
                    <td>
                        {{ number_format($p->paid_amount,2) }} MGA
                        @if($p->paid_amount > $p->amount)
                            <br><small class="text-muted">Rendu: {{ number_format($p->paid_amount - $p->amount,2) }} MGA</small>
                        @endif
                    </td>
                    <td>{{ $p->method }}</td>
                    <td>
                        @if($p->method === 'mobile_money')
                            {{-- show the mobile number instead of the word "mobile money" --}}
                            {{ $p->mobile_number }}
                        @else
                            {{ ucfirst(str_replace('_',' ',$p->method)) }}
                        @endif
                        @if($p->method === 'card' && $p->card_number)
                            <br><small class="text-muted">{{ $p->card_number }}</small>
                        @endif
                    </td>
                    {{-- display the paid_at timestamp if available, otherwise fall back to created_at --}}
                    <td>{{ optional($p->paid_at ?? $p->created_at)->format('Y-m-d H:i:s') }}</td>
                    <td><a href="{{ route('payments.show', $p) }}" class="btn btn-sm btn-outline-primary">Voir</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $payments->links() }}
@endsection
