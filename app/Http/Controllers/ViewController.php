<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            // Si ya está logueado, cerrar sesión y mostrar login
            Auth::logout();
            return redirect('/login')->with('success', 'Sesión cerrada. Por favor inicia sesión con otro usuario.');
        }
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Generar token para API
            $token = Auth::user()->createToken('auth-token')->plainTextToken;
            session(['api_token' => $token]);
            
            return redirect('/dashboard')->with('success', 'Login exitoso');
        }

        return back()->with('error', 'Credenciales incorrectas');
    }

    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        
        return view('dashboard', ['user' => Auth::user()]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Sesión cerrada correctamente');
    }
}
