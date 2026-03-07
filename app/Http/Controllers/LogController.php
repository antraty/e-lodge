<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // only show logs associated with a user; legacy entries with null user
        // or vague descriptions are omitted to avoid confusion.
        $query = ActivityLog::with('user')
            ->whereNotNull('user_id')
            ->where(function($q) {
                $q->where('description', 'not like', '%Unknown%')
                  ->orWhereNull('description');
            });

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('action', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
        }

        $logs = $query->latest()->paginate(20);
        return view('admin.logs', ['logs' => $logs]);
    }

    public function show($id)
    {
        $log = ActivityLog::findOrFail($id);
        return view('admin.logs-show', ['log' => $log]);
    }
}
