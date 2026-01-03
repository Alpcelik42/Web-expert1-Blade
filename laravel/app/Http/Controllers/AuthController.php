<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'security_question' => 'required|string|max:255',
            'security_answer' => 'required|string|max:255'
        ]);

        // Als custom vraag gekozen is
        $securityQuestion = $validated['security_question'];
        if ($securityQuestion === 'custom' && $request->has('custom_security_question')) {
            $securityQuestion = $request->custom_security_question;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'security_question' => $securityQuestion,
            'security_answer' => strtolower(trim($validated['security_answer'])),
            'role' => 'user'
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registratie succesvol!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'De opgegeven inloggegevens zijn onjuist.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function profile()
    {
        $user = Auth::user();
        $favorites = $user->favoriteEvents()->with('images')->get();
        $upcomingEvents = $user->upcomingEvents();
        $pastEvents = $user->pastEvents();

        return view('auth.profile', compact('user', 'favorites', 'upcomingEvents', 'pastEvents'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed'
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;

        if ($request->filled('new_password')) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Huidig wachtwoord is incorrect']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('success', 'Profiel succesvol bijgewerkt!');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Controleer of de gebruiker bestaat
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Dit e-mailadres is niet bekend in ons systeem.']);
        }

        // Controleer of gebruiker een beveiligingsvraag heeft
        if (!$user->security_question || !$user->security_answer) {
            return back()->withErrors(['email' => 'Deze gebruiker heeft geen beveiligingsvraag ingesteld. Neem contact op met de beheerder.']);
        }

        // Toon beveiligingsvraag
        return view('auth.security-question', [
            'email' => $request->email,
            'security_question' => $user->security_question
        ]);
    }

    public function verifySecurityAnswer(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'security_answer' => 'required|string'
        ]);

        // Zoek de gebruiker
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Dit e-mailadres is niet bekend in ons systeem.']);
        }

        // Verifieer het antwoord
        if (!$user->verifySecurityAnswer($request->security_answer)) {
            return back()->withErrors(['security_answer' => 'Het antwoord is niet correct.']);
        }

        // Toon het reset formulier
        return view('auth.reset-password-direct', [
            'email' => $request->email,
            'user' => $user
        ]);
    }

    public function resetPasswordDirect(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Zoek de gebruiker
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Dit e-mailadres is niet bekend in ons systeem.']);
        }

        // Update het wachtwoord
        $user->password = Hash::make($request->password);
        $user->save();

        // Log de actie
        \Log::info('Wachtwoord gereset voor gebruiker: ' . $user->email . ' via beveiligingsvraag.');

        // Redirect naar login met succesmelding
        return redirect()->route('login')->with('success', 'Je wachtwoord is succesvol gewijzigd! Je kunt nu inloggen met je nieuwe wachtwoord.');
    }

    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
