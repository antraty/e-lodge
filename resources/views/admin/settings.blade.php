@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-gear"></i> Paramètres Système</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('settings.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-building"></i> Informations Hôtel</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="hotel_name" class="form-label">Nom de l'Hôtel</label>
                            <input type="text" class="form-control @error('hotel_name') is-invalid @enderror" 
                                   id="hotel_name" name="hotel_name" value="{{ old('hotel_name', $settings['hotel_name'] ?? '') }}" required>
                            @error('hotel_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="hotel_email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('hotel_email') is-invalid @enderror" 
                                   id="hotel_email" name="hotel_email" value="{{ old('hotel_email', $settings['hotel_email'] ?? '') }}" required>
                            @error('hotel_email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="hotel_phone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control @error('hotel_phone') is-invalid @enderror" 
                                   id="hotel_phone" name="hotel_phone" value="{{ old('hotel_phone', $settings['hotel_phone'] ?? '') }}" required>
                            @error('hotel_phone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-sliders"></i> Préférences</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="currency" class="form-label">Devise</label>
                            <input type="text" class="form-control @error('currency') is-invalid @enderror" 
                                   id="currency" name="currency" value="{{ old('currency', $settings['currency'] ?? 'MGA') }}" required maxlength="5">
                            @error('currency')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="items_per_page" class="form-label">Éléments par page</label>
                            <input type="number" class="form-control @error('items_per_page') is-invalid @enderror" 
                                   id="items_per_page" name="items_per_page" value="{{ old('items_per_page', $settings['items_per_page'] ?? 10) }}" 
                                   min="5" max="100" required>
                            @error('items_per_page')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="reservation_notification" name="reservation_notification" value="on"
                                       {{ ($settings['reservation_notification'] ?? 'off') === 'on' ? 'checked' : '' }}>
                                <label class="form-check-label" for="reservation_notification">
                                    Notifications de réservation
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="dark_mode_default" name="dark_mode_default" value="on"
                                       {{ ($settings['dark_mode_default'] ?? 'off') === 'on' ? 'checked' : '' }}>
                                <label class="form-check-label" for="dark_mode_default">
                                    Mode sombre par défaut
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Enregistrer</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
        </div>
    </form>
</div>

<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px 10px 0 0;
    }

    .dark-mode .card {
        background-color: #2d3748;
    }

    .dark-mode .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endsection