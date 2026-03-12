<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    /**
     * Affiche la page de connexion administrateur
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traite la soumission du formulaire de connexion
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $remember = $request->filled('remember');
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Connexion',
                'description' => 'Utilisateur ' . auth()->user()->email . ' connecté',
                'ip_address' => $request->ip(),
            ]);
            
            return redirect('/admin/dashboard')->with('success', 'Connexion réussie!');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Traite la déconnexion
     */
    public function logout(Request $request)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Déconnexion',
            'description' => 'Utilisateur ' . auth()->user()->email . ' déconnecté',
            'ip_address' => $request->ip(),
        ]);
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Déconnecté avec succès!');
    }
}
