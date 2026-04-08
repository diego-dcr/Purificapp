<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Output;
use App\Models\User;
use App\Models\Route;
use App\Models\CarboyOutput;

use Illuminate\Support\Facades\Auth;

class OutputController extends Controller
{
    public function index()
    {
        $outputs = Output::with('user', 'route', 'carboyOutputs')
            ->orderBy('timestamp', 'desc')
            ->get()
            ->map(function ($output) {
                $output->carboy_count = $output->carboyOutputs->count();
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
            'carboy_codebars' => 'nullable|array',
            'carboy_codebars.*' => 'string|max:255',
        ]);

        $output = Output::create([
            'user_id' => $validated['user_id'],
            'route_id' => $validated['route_id'] ?? null,
            'created_by' => Auth::id(),
            'timestamp' => now(),
        ]);

        if (!empty($validated['carboy_codebars'])) {
            foreach ($validated['carboy_codebars'] as $codebar) {
                CarboyOutput::create([
                    'output_id' => $output->id,
                    'carboy_codebar' => $codebar,
                    'timestamp' => now(),
                ]);
            }
        }

        return redirect()->route('retornos.index')->with('success', 'Output registrado exitosamente');
    }

    public function edit(Output $output)
    {
        $output->load('user', 'route', 'carboyOutputs');
        $users = User::all();
        $routes = Route::all();
        $outputs = Output::with('user', 'route', 'carboyOutputs')
            ->orderBy('timestamp', 'desc')
            ->get()
            ->map(function ($item) {
                $item->carboy_count = $item->carboyOutputs->count();
                return $item;
            });

        return view('layouts.output.index', compact('output', 'outputs', 'users', 'routes'));
    }

    public function update(Request $request, Output $output)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'route_id' => 'nullable|exists:routes,id',
            'carboy_codebars' => 'nullable|array',
            'carboy_codebars.*' => 'string|max:255',
        ]);

        $output->update([
            'user_id' => $validated['user_id'],
            'route_id' => $validated['route_id'] ?? null,
            'created_by' => Auth::id(),
        ]);

        if (!empty($validated['carboy_codebars'])) {
            $output->carboyOutputs()->delete();

            foreach ($validated['carboy_codebars'] as $codebar) {
                CarboyOutput::create([
                    'output_id' => $output->id,
                    'carboy_codebar' => $codebar,
                    'timestamp' => now(),
                ]);
            }
        }

        return redirect()->route('retornos.index')->with('success', 'Output actualizado exitosamente');
    }

    public function destroy(Output $output)
    {
        $output->carboyOutputs()->delete();
        $output->delete();

        return redirect()->route('retornos.index')->with('success', 'Output eliminado exitosamente');
    }
}
