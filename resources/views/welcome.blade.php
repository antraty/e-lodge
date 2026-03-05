@extends('layouts.app')

@section('title', 'Accueil - E-Lodge')

@section('content')
<div class="text-center mb-4 mt-4">
    <h1 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem;">
        <i class="fas fa-hotel"></i> Bienvenue sur E-Lodge
    </h1>
    <p style="font-size: 1.1rem; color: var(--muted);">
        Système de gestion d'hôtel complet et intuitif
    </p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <h3><i class="fas fa-door-open" style="color: var(--primary);"></i> Chambres</h3>
                <p class="mb-2">Gérez l'inventaire complet de vos chambres</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('chambres.index') }}" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-list"></i> Voir
                    </a>
                    <a href="{{ route('chambres.create') }}" class="btn btn-secondary flex-grow-1">
                        <i class="fas fa-plus"></i> Ajouter
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <h3><i class="fas fa-users" style="color: var(--primary);"></i> Clients</h3>
                <p class="mb-2">Gérez tous vos clients et leurs informations</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('clients.index') }}" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-list"></i> Voir
                    </a>
                    <a href="{{ route('clients.create') }}" class="btn btn-secondary flex-grow-1">
                        <i class="fas fa-plus"></i> Ajouter
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <h3><i class="fas fa-calendar" style="color: var(--primary);"></i> Réservations</h3>
                <p class="mb-2">Gérez les réservations de vos clients</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('reservations.index') }}" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-list"></i> Voir
                    </a>
                    <a href="{{ route('reservations.create') }}" class="btn btn-secondary flex-grow-1">
                        <i class="fas fa-plus"></i> Ajouter
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <h3><i class="fas fa-credit-card" style="color: var(--primary);"></i> Paiements</h3>
                <p class="mb-2">Suivi des paiements et des factures</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('paiements.index') }}" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-list"></i> Voir
                    </a>
                    <a href="{{ route('paiements.create') }}" class="btn btn-secondary flex-grow-1">
                        <i class="fas fa-plus"></i> Ajouter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection