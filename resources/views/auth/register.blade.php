@extends('layouts.app')

@section('title', 'Inscription - E-Lodge')

@section('content')
<div class="row">
    <div style="grid-column: span 1; max-width: 450px;">
        <div class="card mt-5">
            <div class="card-body">
                <h2 class="mb-4 text-center">
                    <i class="fas fa-user-plus"></i> Inscription
                </h2>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nom complet *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required>
                        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                        @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Mot de passe *</label>
                        <input type="password" name="password" required>
                        @error('password')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Confirmer le mot de passe *</label>
                        <input type="password" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-user-plus"></i> S'inscrire
                    </button>
                </form>

                <div class="mt-3 text-center">
                    <p>Vous avez déjà un compte ? 
                        <a href="{{ route('login') }}" style="color: var(--secondary); font-weight: 600;">
                            Se connecter
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection