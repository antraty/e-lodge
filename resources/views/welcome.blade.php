<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>E-Lodge - Accueil</title>
</head>
<body>
    <h1>Bienvenue sur E-Lodge</h1>
    <p>Gérez facilement vos chambres.</p>
    <ul>
        <li><a href="{{ route('chambres.index') }}">Voir la liste des chambres</a></li>
        <li><a href="{{ route('chambres.create') }}">Ajouter une nouvelle chambre</a></li>
    </ul>
</body>
</html>