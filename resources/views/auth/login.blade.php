@extends('layouts.app')

@section('title', 'Connexion - E-Lodge')

@section('content')
<div class="row">
    <div style="grid-column: span 1; max-width: 450px;">
        <div class="card mt-5">
            <div class="card-body">
                <h2 class="mb-4 text-center">
                    <i class="fas fa-sign-in-alt"></i> Connexion
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

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Mot de passe *</label>
                        <input type="password" name="password" required>
                        @error('password')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </button>
                </form>

                <div class="mt-3 text-center">
                    <p>Pas encore de compte ? 
                        <a href="{{ route('register') }}" style="color: var(--secondary); font-weight: 600;">
                            S'inscrire
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection