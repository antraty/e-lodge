@extends('layouts.app')

@section('content')
    <h2>Liste des paiements</h2>
    <a href="{{ route('paiements.create') }}">Nouveau paiement</a>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Date de paiement</th>
            <th>Montant</th>
            <th>Mode de paiement</th>
            <th>Réservation</th>
            <th>Facture</th>
        </tr>
        @foreach($paiements as $paiement)
            <tr>
                <td>{{ $paiement->id }}</td>
                <td>{{ $paiement->date_paiement }}</td>
                <td>{{ $paiement->montant }}</td>
                <td>{{ $paiement->mode_paiement }}</td>
                <td>{{ $paiement->reservation_id }}</td>
                <td>{{ $paiement->facture_id }}</td>
                <td>
                    <form action="{{ route('paiements.destroy', $paiement->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Supprimer ce paiement ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@endsection