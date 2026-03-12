<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\Client;
use App\Models\Payment;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalRooms = Room::count();
        $availableRooms = Room::available()->count();

        // occupied rooms determined by reservations overlapping today
        $occupiedRooms = Reservation::where('check_in', '<=', $today->toDateString())
            ->where('check_out', '>=', $today->toDateString())
            ->count();

        $totalClients = Client::count();

        $revenueToday = Payment::whereDate('paid_at', $today->toDateString())->sum('paid_amount');

        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;

        $recentLogs = ActivityLog::with('user')->latest()->get();

        return view('dashboard', compact(
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'totalClients',
            'revenueToday',
            'occupancyRate',
            'recentLogs'
        ));
    }
}
