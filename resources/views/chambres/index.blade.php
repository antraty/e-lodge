@extends('layouts.app')

@section('title', 'Chambres - E-Lodge')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2><i class="fas fa-door-open"></i> Chambres</h2>
    <a href="{{ route('chambres.create') }}" class="btn btn-secondary">
        <i class="fas fa-plus"></i> Nouvelle
    </a>
</div>

@if($chambres->isEmpty())
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle"></i>
        <div>Aucune chambre. <a href="{{ route('chambres.create') }}">Cliquez ici</a></div>
    </div>
@else
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> N°</th>
                    <th><i class="fas fa-tag"></i> Type</th>
                    <th><i class="fas fa-door-open"></i> Statut</th>
                    <th><i class="fas fa-money-bill"></i> Prix</th>
                    <th><i class="fas fa-cog"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chambres as $chambre)
                <tr>
                    <td><strong>{{ $chambre->numero }}</strong></td>
                    <td>{{ $chambre->type }}</td>
                    <td>
                        <span class="badge {{ $chambre->status == 'libre' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($chambre->status) }}
                        </span>
                    </td>
                    <td>{{ number_format($chambre->prix, 0) }} MGA</td>
                    <td>
                        <div class="action-links">
                            <a href="{{ route('chambres.edit', $chambre->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form action="{{ route('chambres.destroy', $chambre->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Confirmer ?')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection