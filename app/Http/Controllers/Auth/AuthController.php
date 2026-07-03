<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Aprendiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'rol' => ['required', 'in:administrador,instructor,aprendiz'],
            'documento' => ['required_if:rol,aprendiz', 'nullable', 'string', 'max:20'],
            'programa_formacion' => ['required_if:rol,aprendiz', 'nullable', 'string', 'max:255'],
            'ficha' => ['required_if:rol,aprendiz', 'nullable', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'telefono' => $request->telefono,
        ]);

        if ($request->rol === 'aprendiz') {
            Aprendiz::create([
                'user_id' => $user->id,
                'documento' => $request->documento,
                'telefono' => $request->telefono,
                'programa_formacion' => $request->programa_formacion,
                'ficha' => $request->ficha,
                'fecha_inicio' => now()->toDateString(),
                'estado' => 'activo',
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', '¡Bienvenido al sistema!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }
}
