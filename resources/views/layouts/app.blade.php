<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'E-lodge')</title>
</head>
<body>
    <header>
        <h1>En-tête commun</h1>
        <nav>
            <ul>
                <li>
                    <a href="{{ route('chambres.index') }}">Chambres</a>
                </li>
                <li>
                    <a href="{{ route('clients.index') }}">Clients</a>
                </li>
                <li>
                    <a href="{{ route('reservations.index') }}">Reservations</a>
                </li>
                <li>
                    <a href="{{ route('paiements.index') }}">Paiements</a>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
    <footer>
        <p>Pied de page commun</p>
    </footer>
</body>
</html>