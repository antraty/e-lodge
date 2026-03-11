<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'hotel_name' => Setting::get('hotel_name', 'e-Lodge'),
            'hotel_email' => Setting::get('hotel_email', 'info@elodge.mg'),
            'hotel_phone' => Setting::get('hotel_phone', '+261 34 XX XX XX'),
            'currency' => Setting::get('currency', 'MGA'),
            'items_per_page' => Setting::get('items_per_page', '10'),
            'reservation_notification' => Setting::get('reservation_notification', 'on'),
            'dark_mode_default' => Setting::get('dark_mode_default', 'off'),
        ];
        return view('admin.settings', ['settings' => $settings]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_name' => 'required|string|max:100',
            'hotel_email' => 'required|email',
            'hotel_phone' => 'required|string|max:20',
            'currency' => 'required|string|max:5',
            'items_per_page' => 'required|integer|min:5|max:100',
            'reservation_notification' => 'nullable|in:on,off',
            'dark_mode_default' => 'nullable|in:on,off',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Mise à jour',
            'description' => 'Paramètres généraux mis à jour',
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('settings.index')->with('success', 'Paramètres mis à jour avec succès.');
    }
}
