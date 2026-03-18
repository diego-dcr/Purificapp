<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\User;
use App\Models\Route;

class RouteController extends Controller
{
    public function index()
    {
        $users = User::all();
        $routes = Route::with('user')->get();

        return view('layouts.route.index', compact('users', 'routes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'zone' => 'required|string|max:255',
        ]);

        $lastRouteId = Route::max('id') ?? 0;

        // Generate code from name and zone (e.g., "CENTRO-1")
        $code = strtoupper(substr($validated['zone'], 0, 3) . '-' . substr($validated['name'], 0, 3) . '-' . ($lastRouteId + 1));

        Route::create([
            ...$validated,
            'code' => $code,
        ]);

        return redirect()->route('routes.index')->with('success', 'Ruta creada exitosamente');
    }

    public function edit(Route $route)
    {
        $users = User::all();
        $routes = Route::with('user')->get();

        return view('layouts.route.index', compact('users', 'routes', 'route'));
    }

    public function update(Request $request, Route $route)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'zone' => 'required|string|max:255',
        ]);

        $route->update($validated);

        return redirect()->route('routes.index')->with('success', 'Ruta actualizada exitosamente');
    }

    public function destroy(Route $route)
    {
        $route->delete();

        return redirect()->route('routes.index')->with('success', 'Ruta eliminada exitosamente');
    }
}
