<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Concept;
use App\Models\Customer;
use App\Models\Input;
use App\Models\Route;
use App\Models\User;
use App\Models\WaterjugSale;

use Illuminate\Support\Facades\Auth;

class InputController extends Controller
{
    public function index()
    {
        $inputs = Input::with('user', 'route', 'customer', 'concept', 'waterjugSales')
            ->orderBy('timestamp', 'desc')
            ->get()
            ->map(function ($input) {
                $input->waterjug_count = $input->waterjugSales->count();
                return $input;
            });

        $users = User::all();
        $routes = Route::all();
        $customers = Customer::with('route')->get();
        $concepts = Concept::all();

        return view('layouts.input.index', compact('inputs', 'users', 'routes', 'customers', 'concepts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'route_id' => 'nullable|exists:routes,id',
            'customer_id' => 'required|exists:customers,id',
            'cost' => 'required|numeric|min:0',
            'concept_id' => 'required|exists:concepts,id',
            'waterjug_codebars' => 'nullable|array',
            'waterjug_codebars.*' => 'nullable|string|max:255',
        ]);

        $input = Input::create([
            'user_id' => $validated['user_id'],
            'route_id' => $validated['route_id'] ?? null,
            'customer_id' => $validated['customer_id'],
            'cost' => $validated['cost'],
            'concept_id' => $validated['concept_id'],
            'created_by' => Auth::id(),
            'timestamp' => now(),
        ]);

        $codebars = collect($validated['waterjug_codebars'] ?? [])
            ->map(fn ($codebar) => trim((string) $codebar))
            ->filter()
            ->values();

        foreach ($codebars as $codebar) {
            WaterjugSale::create([
                'input_id' => $input->id,
                'waterjug_codebar' => $codebar,
                'timestamp' => now(),
            ]);
        }

        return redirect()->route('inputs.index')->with('success', 'Entrega/Venta registrada exitosamente');
    }

    public function edit(Input $input)
    {
        $input->load('user', 'route', 'customer', 'concept', 'waterjugSales');

        $inputs = Input::with('user', 'route', 'customer', 'concept', 'waterjugSales')
            ->orderBy('timestamp', 'desc')
            ->get()
            ->map(function ($inputItem) {
                $inputItem->waterjug_count = $inputItem->waterjugSales->count();
                return $inputItem;
            });

        $users = User::all();
        $routes = Route::all();
        $customers = Customer::with('route')->get();
        $concepts = Concept::all();

        return view('layouts.input.index', compact('input', 'inputs', 'users', 'routes', 'customers', 'concepts'));
    }

    public function update(Request $request, Input $input)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'route_id' => 'nullable|exists:routes,id',
            'customer_id' => 'required|exists:customers,id',
            'cost' => 'required|numeric|min:0',
            'concept_id' => 'required|exists:concepts,id',
            'waterjug_codebars' => 'nullable|array',
            'waterjug_codebars.*' => 'nullable|string|max:255',
        ]);

        $input->update([
            'user_id' => $validated['user_id'],
            'route_id' => $validated['route_id'] ?? null,
            'customer_id' => $validated['customer_id'],
            'cost' => $validated['cost'],
            'concept_id' => $validated['concept_id'],
            'created_by' => Auth::id(),
        ]);

        $input->waterjugSales()->delete();

        $codebars = collect($validated['waterjug_codebars'] ?? [])
            ->map(fn ($codebar) => trim((string) $codebar))
            ->filter()
            ->values();

        foreach ($codebars as $codebar) {
            WaterjugSale::create([
                'input_id' => $input->id,
                'waterjug_codebar' => $codebar,
                'timestamp' => now(),
            ]);
        }

        return redirect()->route('inputs.index')->with('success', 'Entrega/Venta actualizada exitosamente');
    }

    public function destroy(Input $input)
    {
        $input->waterjugSales()->delete();
        $input->delete();

        return redirect()->route('inputs.index')->with('success', 'Entrega/Venta eliminada exitosamente');
    }
}
