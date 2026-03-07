<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) return;

        $activities = [
            ['action' => 'Connexion', 'description' => 'Connexion de l\'administrateur'],
            ['action' => 'Création', 'description' => 'Nouvelle chambre créée (Suite Deluxe)'],
            ['action' => 'Création', 'description' => 'Nouveau client enregistré (Jean Dupont)'],
            ['action' => 'Création', 'description' => 'Nouvelle réservation (Réf: RES001)'],
            ['action' => 'Mise à jour', 'description' => 'Réservation RES001 mise à jour'],
            ['action' => 'Création', 'description' => 'Paiement enregistré (500,000 MGA)'],
            ['action' => 'Mise à jour', 'description' => 'Paramètres système sauvegardés'],
        ];

        foreach ($activities as $activity) {
            ActivityLog::create([
                'user_id' => $admin->id,
                'action' => $activity['action'],
                'description' => $activity['description'],
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subMinutes(rand(1, 60)),
            ]);
        }
    }
}
