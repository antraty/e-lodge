@extends('layouts.app')

@section('title', 'Paiements - E-Lodge')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2><i class="fas fa-credit-card"></i> Paiements</h2>
    <a href="{{ route('paiements.create') }}" class="btn btn-secondary">
        <i class="fas fa-plus"></i> Nouveau
    </a>
</div>

@if($paiements->isEmpty())
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle"></i>
        <div>Aucun paiement. <a href="{{ route('paiements.create') }}">Cliquez ici</a></div>
    </div>
@else
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> ID</th>
                    <th><i class="fas fa-calendar"></i> Date</th>
                    <th><i class="fas fa-money-bill-wave"></i> Montant</th>
                    <th><i class="fas fa-wallet"></i> Mode</th>
                    <th><i class="fas fa-hashtag"></i> Réservation</th>
                    <th><i class="fas fa-file-invoice"></i> Facture</th>
                    <th><i class="fas fa-cog"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paiements as $paiement)
                <tr>
                    <td><strong>#{{ $paiement->id }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</td>
                    <td>{{ number_format($paiement->montant, 0) }} MGA</td>
                    <td>{{ $paiement->mode_paiement }}</td>
                    <td>#{{ $paiement->reservation_id }}</td>
                    <td>#{{ $paiement->facture_id }}</td>
                    <td>
                        <div class="action-links">
                            <a href="{{ route('paiements.edit', $paiement->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form action="{{ route('paiements.destroy', $paiement->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Confirmer ?')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection