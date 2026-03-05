@extends('layouts.app')

@section('title', 'Facture - E-Lodge')

@section('content')
<div class="row">
    <div style="grid-column: span 2; max-width: 600px;">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-4 text-center">
                    <i class="fas fa-file-invoice"></i> Facture #{{ $facture->id }}
                </h2>

                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="border: none; padding: 0.8rem;"><strong><i class="fas fa-calendar"></i> Date :</strong></td>
                        <td style="border: none; padding: 0.8rem;">{{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 0.8rem;"><strong><i class="fas fa-money-bill-wave"></i> Montant :</strong></td>
                        <td style="border: none; padding: 0.8rem;">{{ number_format($facture->montant_total, 0) }} MGA</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 0.8rem;"><strong><i class="fas fa-calendar-check"></i> Réservation :</strong></td>
                        <td style="border: none; padding: 0.8rem;">#{{ $facture->reservation_id }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 0.8rem;"><strong><i class="fas fa-check"></i> Statut :</strong></td>
                        <td style="border: none; padding: 0.8rem;">
                            <span class="badge bg-success"><i class="fas fa-check"></i> Générée</span>
                        </td>
                    </tr>
                </table>

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('reservations.index') }}" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-arrow-left"></i> Réservations
                    </a>
                    <a href="{{ route('paiements.create') }}" class="btn btn-secondary flex-grow-1">
                        <i class="fas fa-money-bill-wave"></i> Paiement
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection