@extends('layouts.app')

@section('title', 'Nouvelle chambre - E-Lodge')

@section('content')
<div class="row">
    <div style="grid-column: span 1; max-width: 500px;">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-3"><i class="fas fa-door-open"></i> Ajouter une Chambre</h2>

                <form action="{{ route('chambres.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label><i class="fas fa-hashtag"></i> Numéro *</label>
                        <input type="text" name="numero" value="{{ old('numero') }}" required>
                        @error('numero')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Type *</label>
                        <select name="type" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="Single" {{ old('type') == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Double" {{ old('type') == 'Double' ? 'selected' : '' }}>Double</option>
                            <option value="Suite" {{ old('type') == 'Suite' ? 'selected' : '' }}>Suite</option>
                            <option value="Luxe" {{ old('type') == 'Luxe' ? 'selected' : '' }}>Luxe</option>
                        </select>
                        @error('type')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-door-open"></i> Statut *</label>
                        <select name="status" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="libre" {{ old('status') == 'libre' ? 'selected' : '' }}>Libre</option>
                            <option value="occupée" {{ old('status') == 'occupée' ? 'selected' : '' }}>Occupée</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('status')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-money-bill"></i> Prix (MGA) *</label>
                        <input type="number" name="prix" value="{{ old('prix') }}" step="1000" required>
                        @error('prix')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-secondary flex-grow-1">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <a href="{{ route('chambres.index') }}" class="btn btn-outline flex-grow-1">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection