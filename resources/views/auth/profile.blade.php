@extends('layouts.app')

@section('title', 'Profil - E-Lodge')

@section('content')
<div class="row">
    <div style="grid-column: span 2; max-width: 700px;">
        <!-- Informations Personnelles -->
        <div class="card">
            <div class="card-body">
                <h2 class="mb-4">
                    <i class="fas fa-user-circle"></i> Mon Profil
                </h2>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div>{{ session('success') }}</div>
                        <button class="btn-close" onclick="this.parentElement.remove();">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                <!-- Section 1 : Informations -->
                <div style="border-bottom: 2px solid var(--border); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                    <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.1rem;">
                        <i class="fas fa-info-circle"></i> Informations Personnelles
                    </h3>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div style="grid-column: span 1;">
                                <div class="form-group">
                                    <label><i class="fas fa-user"></i> Nom complet *</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div style="grid-column: span 1;">
                                <div class="form-group">
                                    <label><i class="fas fa-envelope"></i> Email *</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div style="grid-column: span 1;">
                                <div class="form-group">
                                    <label><i class="fas fa-shield-alt"></i> Rôle</label>
                                    <input type="text" value="{{ ucfirst($user->role) }}" disabled style="background-color: var(--light); cursor: not-allowed;">
                                </div>
                            </div>

                            <div style="grid-column: span 1;">
                                <div class="form-group">
                                    <label><i class="fas fa-calendar"></i> Membre depuis</label>
                                    <input type="text" value="{{ $user->created_at->format('d/m/Y à H:i') }}" disabled style="background-color: var(--light); cursor: not-allowed;">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour les informations
                        </button>
                    </form>
                </div>

                <!-- Section 2 : Changer le mot de passe -->
                <div id="password-section">
                    <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.1rem;">
                        <i class="fas fa-key"></i> Changer le Mot de Passe
                    </h3>

                    @if($errors->has('current_password') || $errors->has('password'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                @if($errors->has('current_password'))
                                    {{ $errors->first('current_password') }}
                                @endif
                                @if($errors->has('password'))
                                    {{ $errors->first('password') }}
                                @endif
                            </div>
                            <button class="btn-close" onclick="this.parentElement.remove();">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label><i class="fas fa-lock"></i> Mot de passe actuel *</label>
                            <input type="password" name="current_password" required>
                            @error('current_password')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div style="grid-column: span 1;">
                                <div class="form-group">
                                    <label><i class="fas fa-lock"></i> Nouveau mot de passe *</label>
                                    <input type="password" name="password" required>
                                    @error('password')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div style="grid-column: span 1;">
                                <div class="form-group">
                                    <label><i class="fas fa-lock"></i> Confirmer le mot de passe *</label>
                                    <input type="password" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" style="background-color: var(--light); padding: 1rem; border-radius: 5px; font-size: 0.9rem; color: var(--muted);">
                            <i class="fas fa-info-circle"></i>
                            <strong>Conseils pour un mot de passe sécurisé :</strong>
                            <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                                <li>Minimum 6 caractères</li>
                                <li>Mélangez majuscules et minuscules</li>
                                <li>Ajoutez des chiffres et des caractères spéciaux</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-key"></i> Changer le mot de passe
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Boutons de navigation -->
        <div class="mt-4 d-flex gap-2">
            <a href="{{ route('welcome') }}" class="btn btn-primary flex-grow-1">
                <i class="fas fa-arrow-left"></i> Retour à l'accueil
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary flex-grow-1">
                <i class="fas fa-chart-line"></i> Tableau de bord
            </a>
        </div>
    </div>
</div>

<style>
    .row {
        margin-top: 2rem;
    }

    #password-section {
        margin-top: 1rem;
    }

    ul {
        margin: 0;
        padding-left: 1.5rem;
    }

    ul li {
        margin: 0.3rem 0;
    }
</style>
@endsection