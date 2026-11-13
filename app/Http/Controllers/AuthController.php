<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * Register, log in and log out, written by hand (no starter kit) so every step is visible.
 */
class AuthController extends Controller
{
    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        // The 'hashed' cast on User hashes the password before it is saved.
        $user = User::create($request->validated());

        // External service: send a welcome email (see app/Mail/WelcomeMail.php).
        Mail::to($user)->send(new WelcomeMail($user));

        Auth::login($user);                 // log the new athlete in straight away
        $request->session()->regenerate();  // new session id (prevents session fixation)

        return redirect()->route('workouts.index')
            ->with('status', "Welcome to GymLog, {$user->name}! Log your first workout.");
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        // Auth::attempt() looks up the user by email and checks the hashed password.
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->onlyInput('email');   // keep the email, never send the password back
        }

        $request->session()->regenerate();

        // intended() = go to the page they tried to open before being sent to login.
        return redirect()->intended(route('workouts.index'))
            ->with('status', 'Welcome back, '.Auth::user()->name.'!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();      // throw the old session away
        $request->session()->regenerateToken(); // new CSRF token

        return redirect()->route('home')->with('status', 'You are logged out.');
    }
}
