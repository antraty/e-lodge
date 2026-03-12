@extends('layouts.app')

@section('title', 'Tableau de bord - E-Lodge')

@section('content')
<div class="container-fluid">
    <!-- Header Stats -->
    <div class="row mt-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card gradient-blue">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted">Chambres Occupées</h6>
                            <h2 class="mb-0">{{ $occupiedRooms ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-door-open text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card gradient-green">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted">Chambres Disponibles</h6>
                            <h2 class="mb-0">{{ $availableRooms ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-check-circle text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card gradient-purple">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted">Clients</h6>
                            <h2 class="mb-0">{{ $totalClients ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-people text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card gradient-orange">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted">Revenu Aujourd'hui</h6>
                            <h2 class="mb-0">{{ number_format($revenueToday ?? 0, 0) }} <span style="font-size: 0.6em;">MGA</span></h2>
                        </div>
                        <i class="bi bi-cash-coin text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h5 class="mb-4"><i class="bi bi-lightning-fill"></i> Accès Rapide</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 mb-3">
            <a href="{{ route('rooms.index') }}" class="card dashboard-card h-100 text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-door-open fs-1 text-primary mb-2"></i>
                    <h6>Chambres</h6>
                    <small class="text-muted">Gestion</small>
                </div>
            </a>
        </div>

        <div class="col-md-2 mb-3">
            <a href="{{ route('reservations.index') }}" class="card dashboard-card h-100 text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check fs-1 text-success mb-2"></i>
                    <h6>Réservations</h6>
                    <small class="text-muted">Gestion</small>
                </div>
            </a>
        </div>

        <div class="col-md-2 mb-3">
            <a href="{{ route('clients.index') }}" class="card dashboard-card h-100 text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-1 text-warning mb-2"></i>
                    <h6>Clients</h6>
                    <small class="text-muted">Gestion</small>
                </div>
            </a>
        </div>

        <div class="col-md-2 mb-3">
            <a href="{{ route('payments.index') }}" class="card dashboard-card h-100 text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-credit-card-2-front fs-1 text-info mb-2"></i>
                    <h6>Facturation</h6>
                    <small class="text-muted">Paiements</small>
                </div>
            </a>
        </div>

        <div class="col-md-2 mb-3">
            <a href="{{ route('users.index') }}" class="card dashboard-card h-100 text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-person-gear fs-1 text-danger mb-2"></i>
                    <h6>Utilisateurs</h6>
                    <small class="text-muted">Gestion</small>
                </div>
            </a>
        </div>

        <div class="col-md-2 mb-3">
            <a href="{{ route('settings.index') }}" class="card dashboard-card h-100 text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-gear fs-1 text-secondary mb-2"></i>
                    <h6>Paramètres</h6>
                    <small class="text-muted">Config</small>
                </div>
            </a>
        </div>
    </div>

    <!-- Logs Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-journal-text"></i> Activité Récente</h6>
                    <a href="{{ route('logs.index') }}" class="btn btn-sm btn-outline-primary">Voir tous</a>
                </div>
                <div class="card-body">
                    @if($recentLogs && count($recentLogs) > 0)
                        <div class="timeline">
                            @foreach($recentLogs->take(10) as $log)
                                <div class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <strong>{{ $log->user->name ?? 'Système' }}</strong> — {{ $log->action }}
                                        <br><small class="text-muted">{{ $log->description }}</small>
                                        <br><small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">Aucune activité enregistrée.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card {
        border: none;
        border-radius: 15px;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card.gradient-blue {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card.gradient-green {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .stat-card.gradient-purple {
        background: linear-gradient(135deg, #a855f7 0%, #d946ef 100%);
    }

    .stat-card.gradient-orange {
        background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
    }

    .stat-card h6 {
        opacity: 0.85;
        margin-bottom: 10px;
    }

    .dashboard-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .dashboard-card:hover {
        border-color: #667eea;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        transform: translateY(-3px);
    }

    .dashboard-card i {
        margin-bottom: 10px;
    }

    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item {
        display: flex;
        margin-bottom: 20px;
        position: relative;
    }

    .timeline-marker {
        width: 12px;
        height: 12px;
        background: #667eea;
        border-radius: 50%;
        margin-top: 5px;
        margin-right: 20px;
        flex-shrink: 0;
    }

    .timeline-content {
        flex: 1;
    }

    .dark-mode .stat-card {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .dark-mode .dashboard-card {
        border-color: #495057;
        background-color: #2d3748;
    }

    .dark-mode .dashboard-card:hover {
        border-color: #667eea;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }
</style>
@endsection
