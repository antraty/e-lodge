<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Client;
use Carbon\Carbon;

class ReservationsSeeder extends Seeder
{
    public function run()
    {
        $clients = Client::all();
        $rooms = Room::all();
        if ($clients->count() == 0 || $rooms->count() == 0) {
            return;
        }

        // Create a few reservations
        $today = Carbon::today();
        foreach ($clients as $idx => $client) {
            $room = $rooms[$idx % $rooms->count()];
            $checkIn = $today->copy()->addDays($idx * 2)->toDateString();
            $checkOut = $today->copy()->addDays($idx * 2 + 2)->toDateString();
            $nights = Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));
            $total = $nights * $room->price_per_night;

            // Avoid duplicate reservations for same client/room/dates
            $exists = Reservation::where('client_id', $client->id)
                ->where('room_id', $room->id)
                ->where('check_in', $checkIn)
                ->where('check_out', $checkOut)
                ->exists();

            if (!$exists) {
                Reservation::create([
                    'client_id' => $client->id,
                    'room_id' => $room->id,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'status' => 'confirmed',
                    'number_of_guests' => min(2, $room->capacity),
                    'total_price' => $total,
                    'special_requests' => null,
                ]);
            }
        }
    }
}
