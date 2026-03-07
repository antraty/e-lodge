<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomsSeeder extends Seeder
{
    public function run()
    {
        $types = ['single','double','suite'];
        for ($i = 1; $i <= 12; $i++) {
            $roomNumber = 100 + $i;
            Room::firstOrCreate([
                'room_number' => $roomNumber,
            ], [
                'type' => $types[$i % count($types)],
                'capacity' => $i % 3 + 1,
                'price_per_night' => 50 + ($i * 5),
                'status' => 'available',
                'description' => 'Chambre confortable #' . $roomNumber,
            ]);
        }
    }
}
