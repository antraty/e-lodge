@extends('layouts.app')

@section('title', 'Tableau de bord - E-Lodge')

@section('content')
<div class="mb-4">
    <h2><i class="fas fa-chart-line"></i> Tableau de Bord</h2>
</div>

<div class="grid">
    <div class="card">
        <div class="card-title">
            <i class="fas fa-door-open" style="color: var(--primary); font-size: 1.2rem;"></i>
            Total Chambres
        </div>
        <div class="card-value">{{ $totalChambres }}</div>
    </div>

    <div class="card">
        <div class="card-title">
            <i class="fas fa-check-circle" style="color: var(--success); font-size: 1.2rem;"></i>
            Disponibles
        </div>
        <div class="card-value" style="color: var(--success);">{{ $chambresDisponibles }}</div>
    </div>

    <div class="card">
        <div class="card-title">
            <i class="fas fa-bed" style="color: var(--danger); font-size: 1.2rem;"></i>
            Occupées
        </div>
        <div class="card-value" style="color: var(--danger);">{{ $chambresOccupees }}</div>
    </div>

    <div class="card">
        <div class="card-title">
            <i class="fas fa-calendar-check" style="color: var(--info); font-size: 1.2rem;"></i>
            Réservations
        </div>
        <div class="card-value" style="color: var(--info);">{{ $reservationsEnCours }}</div>
    </div>

    <div class="card">
        <div class="card-title">
            <i class="fas fa-user" style="color: var(--primary); font-size: 1.2rem;"></i>
            Clients
        </div>
        <div class="card-value">{{ $totalClients }}</div>
    </div>

    <div class="card">
        <div class="card-title">
            <i class="fas fa-coins" style="color: var(--accent); font-size: 1.2rem;"></i>
            Paiements
        </div>
        <div class="card-value" style="color: var(--accent);">{{ number_format($totalPaiements ?? 0, 0) }}</div>
        <div class="card-unit">MGA</div>
    </div>

    <div class="card">
        <div class="card-title">
            <i class="fas fa-calendar-today" style="color: var(--secondary); font-size: 1.2rem;"></i>
            Aujourd'hui
        </div>
        <div class="card-value" style="color: var(--secondary);">{{ $reservationsDuJour }}</div>
    </div>

    <div class="card">
        <div class="card-title">
            <i class="fas fa-percent" style="color: var(--warning); font-size: 1.2rem;"></i>
            Occupation
        </div>
        <div class="card-value" style="color: var(--warning);">{{ $tauxOccupation }}%</div>
    </div>
</div>
@endsection