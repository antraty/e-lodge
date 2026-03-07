@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-journal-text"></i> Journaux d'Activité</h2>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('logs.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="col-md-6">
                    <div class="btn-group w-100" role="group">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Chercher</button>
                        <a href="{{ route('logs.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-counterclockwise"></i> Réinitialiser</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><i class="bi bi-calendar3"></i> Date & Heure</th>
                        <th><i class="bi bi-person"></i> Utilisateur</th>
                        <th><i class="bi bi-lightning-charge"></i> Action</th>
                        <th><i class="bi bi-info-circle"></i> Description</th>
                        <th><i class="bi bi-globe"></i> IP</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $log->user->name ?? 'Système' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $log->action }}</span>
                            </td>
                            <td>
                                <small>{{ strlen($log->description) > 40 ? substr($log->description, 0, 40) . '...' : ($log->description ?? '-') }}</small>
                            </td>
                            <td>
                                <code>{{ $log->ip_address ?? '-' }}</code>
                            </td>
                            <td>
                                <a href="{{ route('logs.show', $log) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> Aucun journal d'activité trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $logs->links() }}
    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .table-light {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .dark-mode .card {
        background-color: #2d3748;
    }

    .dark-mode .table {
        color: #e2e8f0;
    }

    .dark-mode .table-hover tbody tr:hover {
        background-color: #1a202c;
    }
</style>
@endsection
