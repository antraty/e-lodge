<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->route('welcome');
        }
        
        return response()->view('auth.login')
                         ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                         ->header('Pragma', 'no-cache')
                         ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            return redirect()->route('welcome')->with('success', 'Connecté avec succès !');
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (auth()->check()) {
            return redirect()->route('welcome');
        }
        
        return response()->view('auth.register')
                         ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                         ->header('Pragma', 'no-cache')
                         ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('welcome')->with('success', 'Compte créé avec succès !');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
                         ->with('success', 'Déconnecté avec succès !')
                         ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                         ->header('Pragma', 'no-cache')
                         ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }

    public function profile()
    {
        return view('auth.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil mis à jour avec succès !');
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe incorrect.']);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Mot de passe changé avec succès !');
    }
}