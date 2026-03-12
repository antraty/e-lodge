@extends('layouts.app')

@section('title', 'Clients - E-Lodge')

@section('content')
<div class="container py-4">

    {{-- En-tête --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-0">Clients</h2>
            <small class="text-muted">
                {{ $clients->total() }} client{{ $clients->total() > 1 ? 's' : '' }}
                @if(request('search'))
                    &mdash; résultats pour « {{ request('search') }} »
                @endif
            </small>
        </div>
        <a href="{{ route('clients.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i> Ajouter un client
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Recherche --}}
    <form method="GET" action="{{ route('clients.index') }}" class="row g-2 mb-3">
        <div class="col-sm-5">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control"
                       placeholder="Nom, prénom, email, téléphone…"
                       value="{{ request('search') }}" autocomplete="off">
                @if(request('search'))
                    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary" title="Effacer">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="col-sm-auto">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-funnel me-1"></i> Rechercher
            </button>
        </div>
    </form>

    {{-- Tableau --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td class="fw-semibold">{{ $client->first_name }} {{ $client->last_name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->phone ?? '—' }}</td>
                        <td>
                            <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                            <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Supprimer {{ $client->first_name }} {{ $client->last_name }} ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            <i class="bi bi-person-x fs-2 d-block mb-2 opacity-25"></i>
                            Aucun client ne correspond à votre recherche.
                            <a href="{{ route('clients.index') }}" class="d-block mt-2">Réinitialiser</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($clients->hasPages())
    <div class="d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
        <small class="text-muted">
            Affichage {{ $clients->firstItem() }}–{{ $clients->lastItem() }} sur {{ $clients->total() }}
        </small>
        {{ $clients->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>
@endsection