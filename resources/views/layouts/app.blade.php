<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Lodge - Gestion d\'Hôtel')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('extra-css')
</head>
<body>
    <header>
        <div class="container-custom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1><i class="fas fa-hotel"></i>E-Lodge</h1>
                </div>
                <div class="col-md-6">
                    <nav>
                        <ul>
                            <li><a href="{{ route('dashboard') }}"><i class="fas fa-chart-line"></i>Tableau de bord</a></li>
                            <li><a href="{{ route('chambres.index') }}"><i class="fas fa-door-open"></i>Chambres</a></li>
                            <li><a href="{{ route('clients.index') }}"><i class="fas fa-users"></i>Clients</a></li>
                            <li><a href="{{ route('reservations.index') }}"><i class="fas fa-calendar"></i>Réservations</a></li>
                            <li><a href="{{ route('paiements.index') }}"><i class="fas fa-credit-card"></i>Paiements</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container-custom">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>{{ session('success') }}</div>
                    <button class="btn-close" onclick="this.parentElement.remove();">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Erreurs détectées :</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button class="btn-close" onclick="this.parentElement.remove();">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer>
        <p><i class="fas fa-copyright"></i> 2026 E-Lodge - Gestion d'Hôtel Moderne</p>
    </footer>

    @yield('extra-js')
</body>
</html>