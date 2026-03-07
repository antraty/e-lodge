@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-journal-text"></i> Détail du Journal</h2>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-muted">Date & Heure</h6>
                        <p>{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Utilisateur</h6>
                        <p><span class="badge bg-info">{{ $log->user->name ?? 'Système' }}</span></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-muted">Action</h6>
                        <p><span class="badge bg-primary">{{ $log->action }}</span></p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Adresse IP</h6>
                        <p><code>{{ $log->ip_address ?? '-' }}</code></p>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <h6 class="text-muted">Description</h6>
                <div class="p-3 bg-light rounded">
                    {{ $log->description ?? 'Aucune description disponible.' }}
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('logs.index') }}" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .dark-mode .card {
        background-color: #2d3748;
    }

    .dark-mode .bg-light {
        background-color: #1a202c !important;
    }
</style>
@endsection
