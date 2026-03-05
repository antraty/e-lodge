@extends('layouts.app')

@section('title', 'Modifier paiement - E-Lodge')

@section('content')
<div class="row">
    <div style="grid-column: span 2; max-width: 600px;">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-3"><i class="fas fa-edit"></i> Modifier Paiement #{{ $paiement->id }}</h2>

                <form action="{{ route('paiements.update', $paiement->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div style="grid-column: span 1;">
                            <div class="form-group">
                                <label><i class="fas fa-calendar"></i> Date *</label>
                                <input type="date" name="date_paiement" value="{{ old('date_paiement', $paiement->date_paiement) }}" required>
                                @error('date_paiement')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div style="grid-column: span 1;">
                            <div class="form-group">
                                <label><i class="fas fa-money-bill-wave"></i> Montant *</label>
                                <input type="number" name="montant" value="{{ old('montant', $paiement->montant) }}" step="1000" required>
                                @error('montant')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div style="grid-column: span 1;">
                            <div class="form-group">
                                <label><i class="fas fa-wallet"></i> Mode *</label>
                                <select name="mode_paiement" required>
                                    <option value="Espèces" {{ old('mode_paiement', $paiement->mode_paiement) == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                                    <option value="Chèque" {{ old('mode_paiement', $paiement->mode_paiement) == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                                    <option value="Carte bancaire" {{ old('mode_paiement', $paiement->mode_paiement) == 'Carte bancaire' ? 'selected' : '' }}>Carte bancaire</option>
                                    <option value="Virement" {{ old('mode_paiement', $paiement->mode_paiement) == 'Virement' ? 'selected' : '' }}>Virement</option>
                                </select>
                                @error('mode_paiement')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div style="grid-column: span 1;">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-check"></i> Réservation *</label>
                                <select name="reservation_id" required>
                                    @foreach($reservations as $reservation)
                                        <option value="{{ $reservation->id }}" {{ old('reservation_id', $paiement->reservation_id) == $reservation->id ? 'selected' : '' }}>
                                            Réservation #{{ $reservation->id }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('reservation_id')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-file-invoice"></i> Facture *</label>
                        <select name="facture_id" required>
                            @foreach($factures as $facture)
                                <option value="{{ $facture->id }}" {{ old('facture_id', $paiement->facture_id) == $facture->id ? 'selected' : '' }}>
                                    Facture #{{ $facture->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('facture_id')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-secondary flex-grow-1">
                            <i class="fas fa-save"></i> Mettre à jour
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