@extends('layouts.app')

@section('content')
    <h3>Détails du paiement #{{ $payment->id }}</h3>

    @if($payment->trashed())
        <div class="alert alert-danger">Ce paiement a été annulé/supprimé</div>
    @endif

    <table class="table">
        <tr><th>Réservation</th><td>#{{ optional($payment->reservation)->id }} - {{ optional(optional($payment->reservation)->room)->room_number ?? '' }}</td></tr>
        <tr><th>Client</th><td>{{ optional(optional($payment->reservation)->client)->first_name ?? '' }} {{ optional(optional($payment->reservation)->client)->last_name ?? '' }}</td></tr>
        <tr><th>Montant demandé</th><td>{{ number_format($payment->amount,2) }} MGA</td></tr>
        <tr><th>Montant payé</th><td>{{ number_format($payment->paid_amount,2) }} MGA</td></tr>
        @if($payment->paid_amount > $payment->amount)
            <tr><th>Rendu</th><td>{{ number_format($payment->paid_amount - $payment->amount,2) }} MGA</td></tr>
        @endif
        <tr><th>Statut</th><td>
            @if($payment->trashed())
                <span class="badge badge-danger">Supprimé</span>
            @elseif($payment->paid_amount >= $payment->amount)
                <span class="badge badge-success">Payé</span>
            @elseif($payment->paid_amount > 0)
                <span class="badge badge-warning">Paiement partiel</span>
            @else
                <span class="badge badge-secondary">En attente</span>
            @endif
        </td></tr>
        <tr><th>Méthode</th><td>{{ $payment->method }}</td></tr>
        @if($payment->method === 'card' && $payment->card_number)
            <tr><th>Numéro de carte</th><td>{{ $payment->card_number }}</td></tr>
        @endif
        @if($payment->method === 'mobile_money' && $payment->mobile_number)
            <tr><th>Numéro mobile</th><td>{{ $payment->mobile_number }}</td></tr>
        @endif
        <tr><th>Référence</th><td>{{ $payment->reference_number }}</td></tr>
        <tr><th>Date</th><td>{{ optional($payment->paid_at)->format('Y-m-d H:i') }}</td></tr>
        <tr><th>Notes</th><td>{{ $payment->notes }}</td></tr>
    </table>

    <a href="{{ route('payments.index') }}" class="btn btn-secondary">Retour</a>
@endsection
