# 🚀 Quick Start - e-Lodge

## Installation Rapide

```bash
# 1. Installer les dépendances
composer install

# 2. Copier l'env
cp .env.example .env

# 3. Générer la clé
php artisan key:generate

# 4. Configurer la base de données dans .env
# DB_DATABASE=elodge
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Migrer la base
php artisan migrate --force

# 6. Seeder les données
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=SettingSeeder
php artisan db:seed --class=ActivityLogSeeder

# 7. Lancer le serveur
php artisan serve
```

## Accès

- **URL**: http://127.0.0.1:8000
- **Login**: admin@example.com
- **Password**: secret123

## Fonctionnalités Principales

- ✅ Dashboard avec statistiques temps réel
- ✅ Gestion complète des chambres (CRUD)
- ✅ Gestion des clients et réservations
- ✅ Système de paiements
- ✅ Factures PDF
- ✅ Gestion des utilisateurs (admin)
- ✅ Paramètres système personnalisables
- ✅ Logs d'activité complets
- ✅ Mode sombre (toggle navbar)
- ✅ Interface responsive et moderne

## Navigation

| Menu | Lien | Action |
|------|------|--------|
| Dashboard | `/admin/dashboard` | Voir les stats |
| Chambres | `/rooms` | Gérer les chambres |
| Clients | `/clients` | Gérer les clients |
| Réservations | `/reservations` | Gérer les réservations |
| Paiements | `/payments` | Voir les paiements |
| **Admin** | | |
| Utilisateurs | `/users` | Gérer les admins |
| Paramètres | `/settings` | Configurer l'app |
| Logs | `/logs` | Voir l'activité |

## Commandes Utiles

```bash
# Voir toutes les routes
php artisan route:list

# Lancer les tests
php artisan test

# Lancer en watch mode
php artisan watch

# Réinitialiser la base
php artisan migrate:refresh --seed

# Vider le cache
php artisan cache:clear
```

## Support

Pour toute question ou problème, consultez `TESTING.md` ou `UPDATES.md`.

---

**Version**: 2.0  
**Date**: Février 2026  
**Status**: ✅ Production Ready
