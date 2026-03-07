<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('email')->paginate(20);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Créé',
            'description' => "Utilisateur #{$user->id} ({$user->email}) créé",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur créé.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Mise à jour',
            'description' => "Utilisateur #{$user->id} ({$user->email}) mise à jour",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user)
    {
        // prevent deleting self
        if (auth()->id() == $user->id) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $userEmail = $user->email;
        $user->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Suppression',
            'description' => "Utilisateur #{$user->id} ({$userEmail}) supprimé",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé.');
    }
}
