<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Facades\Schema;

class ClientsSeeder extends Seeder
{
    public function run()
    {
        $names = [
            ['first'=>'Marie','last'=>'Dupont','email'=>'marie.dupont@example.com'],
            ['first'=>'Jean','last'=>'Martin','email'=>'jean.martin@example.com'],
            ['first'=>'Amina','last'=>'Raja','email'=>'amina.raja@example.com'],
            ['first'=>'Paul','last'=>'Durand','email'=>'paul.durand@example.com'],
        ];

        $hasNameColumn = Schema::hasColumn('clients', 'name');

        foreach ($names as $n) {
            $data = [
                'first_name' => $n['first'],
                'last_name' => $n['last'],
                'email' => $n['email'],
                'phone' => '034123456' . rand(10,99),
                'address' => 'Rue Exemple, 100',
            ];

            if ($hasNameColumn) {
                $data['name'] = $n['first'] . ' ' . $n['last'];
            }

            Client::firstOrCreate([
                'email' => $n['email'],
            ], $data);
        }
    }
}
