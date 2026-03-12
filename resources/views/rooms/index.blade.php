

@section('title', 'Chambres - E-Lodge')
@extends('layouts.app')
@section('content')
<div class="container py-4">

    {{-- En-tête --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-0">Chambres</h2>
            <small class="text-muted">
                {{ $rooms->total() }} chambre{{ $rooms->total() > 1 ? 's' : '' }}
                @if(request()->hasAny(['search','type','status'])) &mdash; filtrées @endif
            </small>
        </div>
        <a href="{{ route('rooms.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Ajouter une chambre
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtres --}}
    <form method="GET" action="{{ route('rooms.index') }}" class="row g-2 mb-3">
        <div class="col-sm-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Rechercher un numéro…"
                       value="{{ request('search') }}" autocomplete="off">
                @if(request('search'))
                    <a href="{{ route('rooms.index', request()->except('search', 'page')) }}" class="btn btn-outline-secondary" title="Effacer">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </div>

        <div class="col-sm-auto">
            <select name="type" class="form-select" onchange="this.form.submit()">
                <option value="">Tous les types</option>
                @foreach(['single','double','suite','deluxe'] as $t)
                    <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-auto">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                @foreach(['available'=>'Disponible','reserved'=>'Réservée','occupied'=>'Occupée','maintenance'=>'Maintenance'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-auto d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-funnel me-1"></i> Filtrer
            </button>
            @if(request()->hasAny(['search','type','status']))
                <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Réinitialiser
                </a>
            @endif
        </div>
    </form>

    {{-- Tableau --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Numéro</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Prix / nuit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                    <tr>
                        <td class="fw-bold">{{ $room->room_number }}</td>
                        <td><span class="badge text-bg-primary">{{ ucfirst($room->type) }}</span></td>
                        <td>
                            @php
                                $statusColor = match($room->status) {
                                    'available'   => 'success',
                                    'reserved'    => 'info',
                                    'occupied'    => 'danger',
                                    'maintenance' => 'warning',
                                    default       => 'secondary',
                                };
                                $statusLabel = match($room->status) {
                                    'available'   => 'Disponible',
                                    'reserved'    => 'Réservée',
                                    'occupied'    => 'Occupée',
                                    'maintenance' => 'Maintenance',
                                    default       => ucfirst($room->status),
                                };
                            @endphp
                            <span class="badge text-bg-{{ $statusColor }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="fw-semibold text-primary">{{ number_format($room->price_per_night, 0, ',', ' ') }} Ar</td>
                        <td>
                            <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                            <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Supprimer la chambre {{ $room->room_number }} ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-search fs-2 d-block mb-2 opacity-25"></i>
                            Aucune chambre ne correspond à votre recherche.
                            <a href="{{ route('rooms.index') }}" class="d-block mt-2">Réinitialiser les filtres</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($rooms->hasPages())
    <div class="d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
        <small class="text-muted">
            Affichage {{ $rooms->firstItem() }}–{{ $rooms->lastItem() }} sur {{ $rooms->total() }}
        </small>
        {{ $rooms->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>

@if(session('success'))
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast show align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">{{ session('success') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endif
@endsection@extends('layouts.app')

@section('title', 'Chambres - E-Lodge')

@section('content')
<div class="container py-4">

    {{-- En-tête --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-0">Chambres</h2>
            <small class="text-muted">
                {{ $rooms->total() }} chambre{{ $rooms->total() > 1 ? 's' : '' }}
                @if(request()->hasAny(['search','type','status'])) &mdash; filtrées @endif
            </small>
        </div>
        <a href="{{ route('rooms.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Ajouter une chambre
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtres --}}
    <form method="GET" action="{{ route('rooms.index') }}" class="row g-2 mb-3">
        <div class="col-sm-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Rechercher un numéro…"
                       value="{{ request('search') }}" autocomplete="off">
                @if(request('search'))
                    <a href="{{ route('rooms.index', request()->except('search', 'page')) }}" class="btn btn-outline-secondary" title="Effacer">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </div>

        <div class="col-sm-auto">
            <select name="type" class="form-select" onchange="this.form.submit()">
                <option value="">Tous les types</option>
                @foreach(['single','double','suite','deluxe'] as $t)
                    <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-auto">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                @foreach(['available'=>'Disponible','reserved'=>'Réservée','occupied'=>'Occupée','maintenance'=>'Maintenance'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-auto d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-funnel me-1"></i> Filtrer
            </button>
            @if(request()->hasAny(['search','type','status']))
                <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Réinitialiser
                </a>
            @endif
        </div>
    </form>

    {{-- Tableau --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Numéro</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Prix / nuit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                    <tr>
                        <td class="fw-bold">{{ $room->room_number }}</td>
                        <td><span class="badge text-bg-primary">{{ ucfirst($room->type) }}</span></td>
                        <td>
                            @php
                                $statusColor = match($room->status) {
                                    'available'   => 'success',
                                    'reserved'    => 'info',
                                    'occupied'    => 'danger',
                                    'maintenance' => 'warning',
                                    default       => 'secondary',
                                };
                                $statusLabel = match($room->status) {
                                    'available'   => 'Disponible',
                                    'reserved'    => 'Réservée',
                                    'occupied'    => 'Occupée',
                                    'maintenance' => 'Maintenance',
                                    default       => ucfirst($room->status),
                                };
                            @endphp
                            <span class="badge text-bg-{{ $statusColor }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="fw-semibold text-primary">{{ number_format($room->price_per_night, 0, ',', ' ') }} Ar</td>
                        <td>
                            <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                            <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Supprimer la chambre {{ $room->room_number }} ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-search fs-2 d-block mb-2 opacity-25"></i>
                            Aucune chambre ne correspond à votre recherche.
                            <a href="{{ route('rooms.index') }}" class="d-block mt-2">Réinitialiser les filtres</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($rooms->hasPages())
    <div class="d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
        <small class="text-muted">
            Affichage {{ $rooms->firstItem() }}–{{ $rooms->lastItem() }} sur {{ $rooms->total() }}
        </small>
        {{ $rooms->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>

@if(session('success'))
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast show align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">{{ session('success') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endif
@endsection