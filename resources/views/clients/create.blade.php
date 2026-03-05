@extends('layouts.app')

@section('title', 'Nouveau client - E-Lodge')

@section('content')
<div class="row">
    <div style="grid-column: span 1; max-width: 500px;">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-3"><i class="fas fa-user-plus"></i> Ajouter un Client</h2>

                <form action="{{ route('clients.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nom *</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required>
                        @error('nom')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Prénom *</label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" required>
                        @error('prenom')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Téléphone *</label>
                        <input type="text" name="telephone" value="{{ old('telephone') }}" required>
                        @error('telephone')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt"></i> Adresse *</label>
                        <textarea name="adresse" required>{{ old('adresse') }}</textarea>
                        @error('adresse')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-secondary flex-grow-1">
                            <i class="fas fa-save"></i> Ajouter
                        </button>
                        <a href="{{ route('clients.index') }}" class="btn btn-outline flex-grow-1">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection