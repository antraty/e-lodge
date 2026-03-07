<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>e-Lodge Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="bi bi-building"></i> e-Lodge
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="bi bi-house-fill"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('rooms.index') }}">
                            <i class="bi bi-door-open"></i> Chambres
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('clients.index') }}">
                            <i class="bi bi-people"></i> Clients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reservations.index') }}">
                            <i class="bi bi-calendar-check"></i> Réservations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('payments.index') }}">
                            <i class="bi bi-credit-card"></i> Paiements
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navAdminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear-fill"></i> Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navAdminDropdown">
                            <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="bi bi-person-gear"></i> Utilisateurs</a></li>
                            <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="bi bi-sliders"></i> Paramètres</a></li>
                            <li><a class="dropdown-item" href="{{ route('logs.index') }}"><i class="bi bi-journal-text"></i> Logs</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" id="darkModeToggle2"><i class="bi bi-moon-stars"></i> Mode Sombre</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-2" role="search">
                    <button id="darkModeToggle" class="btn btn-outline-light" title="Mode Sombre">
                        <i class="bi bi-moon-stars"></i>
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name ?? auth()->user()->email }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                            <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dark mode toggle
        function setDarkMode(enabled) {
            if (enabled) {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                document.body.classList.add('dark-mode');
            } else {
                document.documentElement.removeAttribute('data-bs-theme');
                document.body.classList.remove('dark-mode');
            }
            localStorage.setItem('darkMode', enabled ? '1' : '0');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const toggle1 = document.getElementById('darkModeToggle');
            const toggle2 = document.getElementById('darkModeToggle2');
            
            const toggleDarkMode = function (e) {
                e.preventDefault();
                const enabled = document.body.classList.contains('dark-mode');
                setDarkMode(!enabled);
            };

            if (toggle1) toggle1.addEventListener('click', toggleDarkMode);
            if (toggle2) toggle2.addEventListener('click', toggleDarkMode);

            // apply preference
            const pref = localStorage.getItem('darkMode');
            if (pref === '1') {
                setDarkMode(true);
            }
        });
    </script>
    <style>
        .bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        body.dark-mode {
            background-color: #1a1a2e;
            color: #e2e8f0;
        }

        body.dark-mode .container, body.dark-mode main {
            background-color: transparent;
        }

        body.dark-mode .card {
            background-color: #2d3748;
            border-color: #4a5568;
            color: #e2e8f0;
        }

        body.dark-mode .card-header {
            border-color: #4a5568;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body.dark-mode .table {
            color: #e2e8f0;
        }

        body.dark-mode .table-light {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        body.dark-mode .table-hover tbody tr:hover {
            background-color: #1a202c;
        }

        body.dark-mode .form-control, body.dark-mode .form-select {
            background-color: #2d3748;
            color: #e2e8f0;
            border-color: #4a5568;
        }

        body.dark-mode .form-control:focus, body.dark-mode .form-select:focus {
            background-color: #374151;
            border-color: #667eea;
            color: #e2e8f0;
        }

        body.dark-mode .alert {
            background-color: #2d3748;
            border-color: #4a5568;
        }

        body.dark-mode .alert-success {
            background-color: #1e3a1f;
            border-color: #2d5a30;
            color: #86efac;
        }

        body.dark-mode .btn-secondary {
            background-color: #4a5568;
            border-color: #4a5568;
        }

        body.dark-mode .btn-secondary:hover {
            background-color: #5a6578;
            border-color: #5a6578;
        }

        body.dark-mode .badge {
            background-color: #667eea;
        }

        body.dark-mode a {
            color: #93c5fd;
        }

        body.dark-mode a:hover {
            color: #bfdbfe;
        }
    </style>
    @yield('scripts')
</body>
</html>