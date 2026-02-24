<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Mon Application')</title>
</head>
<body>
    <header>
        <h1>En-tête commun</h1>
        <nav>
            <ul>
                <li>
                    <a href="{{ route('chambres.index') }}">Chambres</a>
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