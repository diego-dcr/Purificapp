<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\Output;
use App\Models\User;
use App\Models\Route;
use App\Models\WaterjugOutput;

use Illuminate\Support\Facades\Auth;

class OutputController extends Controller
{
    public function index()
    {
        $outputs = Output::with('user', 'route', 'waterjugOutputs')
            ->orderBy('timestamp', 'desc')
            ->get()
            ->map(function ($output) {
                $output->waterjug_count = $output->waterjugOutputs->count();
                return $output;
            });

        $users = User::all();
        $routes = Route::all();

        return view('layouts.output.index', compact('outputs', 'users', 'routes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'route_id' => 'nullable|exists:routes,id',
            'waterjug_codebars' => 'nullable|array',
            'waterjug_codebars.*' => 'string|max:255',
        ]);

        $output = Output::create([
            'user_id' => $validated['user_id'],
            'route_id' => $validated['route_id'] ?? null,
            'created_by' => Auth::id(),
            'timestamp' => now(),
        ]);

        // Crear registros de devoluciones de garrafas si se proporcionan
        if (!empty($validated['waterjug_codebars'])) {
            foreach ($validated['waterjug_codebars'] as $codebar) {
                WaterjugOutput::create([
                    'output_id' => $output->id,
                    'waterjug_codebar' => $codebar,
                    'timestamp' => now(),
                ]);
            }
        }

        return redirect()->route('outputs.index')->with('success', 'Salida registrada exitosamente');
    }

    public function edit(Output $output)
    {
        $output->load('user', 'route', 'waterjugOutputs');
        $users = User::all();
        $routes = Route::all();
        $outputs = Output::with('user', 'route', 'waterjugOutputs')
            ->orderBy('timestamp', 'desc')
            ->get()
            ->map(function ($output) {
                $output->waterjug_count = $output->waterjugOutputs->count();
                return $output;
            });

        return view('layouts.output.index', compact('output', 'outputs', 'users', 'routes'));
    }

    public function update(Request $request, Output $output)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'route_id' => 'nullable|exists:routes,id',
            'waterjug_codebars' => 'nullable|array',
            'waterjug_codebars.*' => 'string|max:255',
        ]);

        $output->update([
            'user_id' => $validated['user_id'],
            'route_id' => $validated['route_id'] ?? null,
            'created_by' => Auth::id(),
        ]);

        // Actualizar devoluciones de garrafas si se proporcionan
        if (!empty($validated['waterjug_codebars'])) {
            // Eliminar registros anteriores
            $output->waterjugOutputs()->delete();

            // Crear nuevos registros
            foreach ($validated['waterjug_codebars'] as $codebar) {
                WaterjugOutput::create([
                    'output_id' => $output->id,
                    'waterjug_codebar' => $codebar,
                    'timestamp' => now(),
                ]);
            }
        }

        return redirect()->route('outputs.index')->with('success', 'Salida actualizada exitosamente');
    }

    public function destroy(Output $output)
    {
        $output->waterjugOutputs()->delete();
        $output->delete();

        return redirect()->route('outputs.index')->with('success', 'Salida eliminada exitosamente');
    }
}
