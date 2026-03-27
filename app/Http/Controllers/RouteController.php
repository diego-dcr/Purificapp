<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'code' => 'required|string|max:255|unique:routes,code',
        ]);

        Route::create($validated);

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
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('routes', 'code')->ignore($route->id),
            ],
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
