<?php

namespace App\Http\Controllers;

use App\Support\Weight;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Remembers kg or lb for this visitor. The SESSION is server-side storage tied to a cookie,
 * so the choice survives page loads (also for guests) without touching the database tables.
 */
class UnitController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'unit' => ['required', Rule::in(Weight::UNITS)],
        ]);

        $request->session()->put('unit', $validated['unit']);

        return back(); // reload the page the user was on
    }
}
