<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'hotel_name' => 'e-Lodge',
            'hotel_email' => 'info@elodge.mg',
            'hotel_phone' => '+261 34 XX XX XX',
            'currency' => 'MGA',
            'items_per_page' => '10',
            'reservation_notification' => 'on',
            'dark_mode_default' => 'off',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
