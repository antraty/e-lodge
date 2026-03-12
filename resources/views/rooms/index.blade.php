@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- En-tête --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-0">Chambres</h2>
            <small class="text-muted">
                {{ $rooms->total() }} chambre{{ $rooms->total() > 1 ? 's' : '' }}
                @if(request()->hasAny(['search','type','status']))
                    &mdash; filtrées
                @endif
            </small>
        </div>
        <a href="{{ route('rooms.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Ajouter une chambre
        </a>
    </div>

    {{-- Filtres --}}
    <form method="GET" action="{{ route('rooms.index') }}" class="row g-2 mb-3">
        <div class="col-sm-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Rechercher un numéro…"
                       value="{{ request('search') }}" autocomplete="off">
            </div>
        </div>

        <div class="col-sm-auto">
            <select name="type" class="form-select" onchange="this.form.submit()">
                <option value="">Tous les types</option>
                @foreach(['single','double','suite','deluxe'] as $t)
                    <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>
                        {{ ucfirst($t) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-auto">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                @foreach(['available','occupied','maintenance'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
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
                        <th>
                            <a href="{{ route('rooms.index', array_merge(request()->query(), ['sort'=>'room_number','dir'=>request('sort')==='room_number'&&request('dir')==='asc'?'desc':'asc'])) }}"
                               class="text-decoration-none text-muted fw-semibold">
                                Numéro
                                @if(request('sort')==='room_number')
                                    <i class="bi bi-arrow-{{ request('dir')==='desc'?'down':'up' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('rooms.index', array_merge(request()->query(), ['sort'=>'type','dir'=>request('sort')==='type'&&request('dir')==='asc'?'desc':'asc'])) }}"
                               class="text-decoration-none text-muted fw-semibold">
                                Type
                                @if(request('sort')==='type')
                                    <i class="bi bi-arrow-{{ request('dir')==='desc'?'down':'up' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('rooms.index', array_merge(request()->query(), ['sort'=>'status','dir'=>request('sort')==='status'&&request('dir')==='asc'?'desc':'asc'])) }}"
                               class="text-decoration-none text-muted fw-semibold">
                                Statut
                                @if(request('sort')==='status')
                                    <i class="bi bi-arrow-{{ request('dir')==='desc'?'down':'up' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('rooms.index', array_merge(request()->query(), ['sort'=>'price_per_night','dir'=>request('sort')==='price_per_night'&&request('dir')==='asc'?'desc':'asc'])) }}"
                               class="text-decoration-none text-muted fw-semibold">
                                Prix / nuit
                                @if(request('sort')==='price_per_night')
                                    <i class="bi bi-arrow-{{ request('dir')==='desc'?'down':'up' }}"></i>
                                @endif
                            </a>
                        </th>
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
                                    'occupied'    => 'danger',
                                    'maintenance' => 'warning',
                                    default       => 'secondary',
                                };
                            @endphp
                            <span class="badge text-bg-{{ $statusColor }}">{{ ucfirst($room->status) }}</span>
                        </td>
                        <td class="fw-semibold text-primary">{{ number_format($room->price_per_night, 0, ',', ' ') }} Ar</td>
                        <td>
                            <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                            <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Supprimer la chambre {{ $room->room_number }} ?')">
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