<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the welcome page.
     * Route (routes/web.php) -> this controller method -> view (resources/views/welcome.blade.php).
     */
    public function index(): View
    {
        return view('welcome', [
            'appName' => 'GymLog',
        ]);
    }
}
