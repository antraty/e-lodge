@extends('layouts.app')

@section('title', 'Nouveau paiement - E-Lodge')

@section('content')
<div class="row">
    <div style="grid-column: span 2; max-width: 600px;">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-3"><i class="fas fa-money-bill-wave"></i> Enregistrer un Paiement</h2>

                <form action="{{ route('paiements.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div style="grid-column: span 1;">
                            <div class="form-group">
                                <label><i class="fas fa-calendar"></i> Date *</label>
                                <input type="date" name="date_paiement" value="{{ old('date_paiement', now()->format('Y-m-d')) }}" required>
                                @error('date_paiement')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div style="grid-column: span 1;">
                            <div class="form-group">
                                <label><i class="fas fa-wallet"></i> Mode *</label>
                                <select name="mode_paiement" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="Espèces" {{ old('mode_paiement') == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                                    <option value="Chèque" {{ old('mode_paiement') == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                                    <option value="Carte bancaire" {{ old('mode_paiement') == 'Carte bancaire' ? 'selected' : '' }}>Carte bancaire</option>
                                    <option value="Virement" {{ old('mode_paiement') == 'Virement' ? 'selected' : '' }}>Virement</option>
                                </select>
                                @error('mode_paiement')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div style="grid-column: span 1;">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-check"></i> Réservation *</label>
                                <select name="reservation_id" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($reservations as $reservation)
                                        <option value="{{ $reservation->id }}" {{ old('reservation_id') == $reservation->id ? 'selected' : '' }}>
                                            Rés. #{{ $reservation->id }} - {{ $reservation->client->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('reservation_id')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div style="grid-column: span 1;">
                            <div class="form-group">
                                <label><i class="fas fa-file-invoice"></i> Facture *</label>
                                <select name="facture_id" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($factures as $facture)
                                        <option value="{{ $facture->id }}" {{ old('facture_id') == $facture->id ? 'selected' : '' }}>
                                            Fac. #{{ $facture->id }} - {{ number_format($facture->montant_total, 0) }} MGA
                                        </option>
                                    @endforeach
                                </select>
                                @error('facture_id')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-secondary flex-grow-1">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <a href="{{ route('paiements.index') }}" class="btn btn-outline flex-grow-1">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection