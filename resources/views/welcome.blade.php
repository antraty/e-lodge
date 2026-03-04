<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>E-Lodge - Accueil</title>
</head>
<body>
    <h1>Bienvenue sur E-Lodge</h1>
    <ul>
        <li><a href="{{ route('chambres.index') }}">Voir la liste des chambres</a></li>
        <li><a href="{{ route('chambres.create') }}">Ajouter une nouvelle chambre</a></li>
        <li><a href="{{ route('clients.index') }}">Voir la liste des clients</a></li>
        <li><a href="{{ route('clients.create') }}">Ajouter un nouveau client</a></li>
        <li><a href="{{ route('reservations.index') }}">Voir la liste des reservations</a></li>
        <li><a href="{{ route('reservations.create') }}">Ajouter un nouvelle réservation</a></li>
    </ul>
</body>
</html>