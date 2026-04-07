<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Retorno;
use App\Models\User;
use App\Models\Route;
use App\Models\CarboyOutput;

use Illuminate\Support\Facades\Auth;

class RetornoController extends Controller
{
    public function index()
    {
        $retornos = Retorno::with('user', 'route', 'carboyRetornos')
            ->orderBy('timestamp', 'desc')
            ->get()
            ->map(function ($retorno) {
                $retorno->carboy_count = $retorno->carboyRetornos->count();
                return $retorno;
            });

        $users = User::all();
        $routes = Route::all();

        return view('layouts.retorno.index', compact('retornos', 'users', 'routes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'route_id' => 'nullable|exists:routes,id',
            'carboy_codebars' => 'nullable|array',
            'carboy_codebars.*' => 'string|max:255',
        ]);

        $retorno = Retorno::create([
            'user_id' => $validated['user_id'],
            'route_id' => $validated['route_id'] ?? null,
            'created_by' => Auth::id(),
            'timestamp' => now(),
        ]);

        if (!empty($validated['carboy_codebars'])) {
            foreach ($validated['carboy_codebars'] as $codebar) {
                CarboyOutput::create([
                    'retorno_id' => $retorno->id,
                    'carboy_codebar' => $codebar,
                    'timestamp' => now(),
                ]);
            }
        }

        return redirect()->route('retornos.index')->with('success', 'Retorno registrado exitosamente');
    }

    public function edit(Retorno $retorno)
    {
        $retorno->load('user', 'route', 'carboyRetornos');
        $users = User::all();
        $routes = Route::all();
        $retornos = Retorno::with('user', 'route', 'carboyRetornos')
            ->orderBy('timestamp', 'desc')
            ->get()
            ->map(function ($item) {
                $item->carboy_count = $item->carboyRetornos->count();
                return $item;
            });

        return view('layouts.retorno.index', compact('retorno', 'retornos', 'users', 'routes'));
    }

    public function update(Request $request, Retorno $retorno)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'route_id' => 'nullable|exists:routes,id',
            'carboy_codebars' => 'nullable|array',
            'carboy_codebars.*' => 'string|max:255',
        ]);

        $retorno->update([
            'user_id' => $validated['user_id'],
            'route_id' => $validated['route_id'] ?? null,
            'created_by' => Auth::id(),
        ]);

        if (!empty($validated['carboy_codebars'])) {
            $retorno->carboyRetornos()->delete();

            foreach ($validated['carboy_codebars'] as $codebar) {
                CarboyOutput::create([
                    'retorno_id' => $retorno->id,
                    'carboy_codebar' => $codebar,
                    'timestamp' => now(),
                ]);
            }
        }

        return redirect()->route('retornos.index')->with('success', 'Retorno actualizado exitosamente');
    }

    public function destroy(Retorno $retorno)
    {
        $retorno->carboyRetornos()->delete();
        $retorno->delete();

        return redirect()->route('retornos.index')->with('success', 'Retorno eliminado exitosamente');
    }
}
