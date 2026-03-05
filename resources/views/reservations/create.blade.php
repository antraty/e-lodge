@extends('layouts.app')

@section('title', 'Nouvelle réservation - E-Lodge')

@section('content')
<div class="row">
    <div style="grid-column: span 2; max-width: 600px;">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-3"><i class="fas fa-calendar-plus"></i> Nouvelle Réservation</h2>

                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div style="grid-column: span 1;">
                            <div class="form-group">
                                <label><i class="fas fa-user"></i> Client *</label>
                                <select name="client_id" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->nom }} {{ $client->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div style="grid-column: span 1;">
                            <div class="form-group">
                                <label><i class="fas fa-door-open"></i> Chambre *</label>
                                <select name="chambre_id" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($chambres as $chambre)
                                        <option value="{{ $chambre->id }}" {{ old('chambre_id') == $chambre->id ? 'selected' : '' }}>
                                            Chambre {{ $chambre->numero }} - {{ $chambre->type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('chambre_id')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div style="grid-column: span 1;">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-plus"></i> Arrivée *</label>
                                <input type="date" name="date_debut" value="{{ old('date_debut') }}" required>
                                @error('date_debut')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div style="grid-column: span 1;">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-minus"></i> Départ *</label>
                                <input type="date" name="date_fin" value="{{ old('date_fin') }}" required>
                                @error('date_fin')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-info-circle"></i> Statut *</label>
                        <select name="status" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="confirmée" {{ old('status') == 'confirmée' ? 'selected' : '' }}>Confirmée</option>
                            <option value="en attente" {{ old('status') == 'en attente' ? 'selected' : '' }}>En attente</option>
                        </select>
                        @error('status')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-secondary flex-grow-1">
                            <i class="fas fa-save"></i> Créer
                        </button>
                        <a href="{{ route('reservations.index') }}" class="btn btn-outline flex-grow-1">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection