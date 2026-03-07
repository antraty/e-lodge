# e-Lodge — Manuel de vérification rapide

Ce document décrit comment exécuter l'application localement, migrer la base, créer l'administrateur (seed), et tester les principaux flux : réservations et paiements.

Prérequis
- PHP >= 8.1 (le projet a été testé avec PHP 8.5)
- Composer
- MySQL (ou MariaDB) et une base configurée dans `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

Commandes utiles

1) Installer les dépendances

```bash
composer install
```

2) Copier le fichier d'environnement et configurer la DB

```bash
cp .env.example .env
# puis éditez .env pour définir DB_*
php artisan key:generate
```                                                                                                                                                                   

3) Migrer la base et exécuter le seeder admin

```bash
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder
```

4) Lancer le serveur de développement

```bash
php artisan serve
# Accéder ensuite à http://127.0.0.1:8000
```

Compte administrateur (seed)
- Email: `admin@example.com`
- Mot de passe: `secret123`

Scénarios de test rapides

1) Connexion
- Aller sur `/login`, utilisez les identifiants admin ci-dessus. Cochez « Se souvenir de moi » si vous souhaitez rester connecté après un arrêt/redémarrage du serveur.

2) Créer une chambre
- Menu `Chambres` → `Nouvelle chambre` (ou Routes: `/rooms/create`)
- Remplir numéro, type, capacité, prix/night, sauvegarder.

3) Créer un client
- Menu `Clients` → `Nouveau client` → remplir et sauvegarder.

4) Créer une réservation
- Menu `Réservations` → `Nouvelle réservation` → choisir client + chambre + dates → créer.
- Vérifier que le `Montant` est calculé automatiquement (nuits * prix/night).

5) Enregistrer un paiement
- Depuis `Réservations` (liste), cliquer sur `Enregistrer un paiement` pour la réservation créée.
- Vérifier que le paiement est créé et que le statut de la réservation passe à `partial` ou `paid` selon le montant payé.
- Consulter `Paiements` pour voir l'historique.

6) Générer / télécharger une facture PDF
- Depuis la page d'une réservation (`Réservations` → `Voir`) cliquer sur "Télécharger la facture (PDF)".
- Si `barryvdh/laravel-dompdf` n'est pas installé, la page affichera un aperçu HTML. Pour activer la génération PDF, installez la dépendance :

```bash
composer require barryvdh/laravel-dompdf
```

La génération de PDF fonctionne ensuite automatiquement.

7) Gestion des utilisateurs
- Dans le menu `Utilisateurs` (coin supérieur droit), listez, créez et modifiez des comptes.
- Créez un nouvel administrateur de test avec un mot de passe simple, puis déconnectez-vous (`/logout`) et reconnectez-vous avec ces identifiants pour vérifier le flux.
- Note : vous ne pouvez pas supprimer l'utilisateur actuellement connecté.

8) Mode Sombre
- Cliquez sur l'icône lune dans la navbar en haut à droite ou dans le menu Admin → Mode Sombre.
- Le mode sombre s'applique instantanément et est mémorisé localement (survit aux redémarrages).
- Tous les éléments (cartes, tableaux, formulaires) s'adaptent à la palette sombre.

9) Paramètres Système
- Allez dans **Admin → Paramètres** pour configurer :
  - Informations hôtel : nom, email, téléphone
  - Devise (par défaut MGA)
  - Nombre d'éléments par page
  - Options de notifications et du thème par défaut
- Les modifications sont immédiatement enregistrées et appliquées.

10) Journaux d'Activité
- Allez dans **Admin → Logs** pour voir l'historique complet des actions système.
- Cliquez sur **Voir** pour consulter les détails d'une action (utilisateur, timestamp, IP, description).
- Les logs s'enregistrent automatiquement pour chaque action importante.

7) Mode sombre
- Sur n'importe quelle page, cliquez sur l'icône lune 🌙 en haut à droite de la barre de navigation. Cela bascule entre le thème clair et sombre.
- La préférence est mémorisée dans le stockage local du navigateur et reste active après un rechargement ou un redémarrage du serveur.

Vérifications supplémentaires
- Dashboard (`/admin/dashboard`) : voir les statistiques (chambres occupées, disponibles, revenu aujourd'hui).
- Tester disponibilité des chambres via l'interface `Chambres` (formulaire de vérification) si présent.

Exécuter la suite de tests unitaires

```bash
./vendor/bin/phpunit --colors=always
```

Dépannage
- Si `phpunit` n'est pas trouvé, utilisez la version embarquée `./vendor/bin/phpunit`.
- Si migration échoue pour `table exists`, il est possible qu'il y ait des migrations dupliquées — vérifiez `database/migrations`.

Support
- Dites-moi si vous souhaitez que j'ajoute :
  - Un lien de la page `reservation.show` vers `Paiement` (la vue show n'existe pas actuellement).
  - Génération de facture PDF (ex. via `barryvdh/laravel-dompdf`).

---
Fait le : {{ date('Y-m-d') }}
