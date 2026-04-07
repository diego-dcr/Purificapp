<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Concept;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Route;
use App\Models\User;
use App\Models\CarboySale;

use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('user', 'route', 'customer', 'concept', 'carboySales')
            ->orderBy('timestamp', 'desc')
            ->get()
            ->map(function ($sale) {
                $sale->carboy_count = $sale->carboySales->count();
                return $sale;
            });

        $users = User::all();
        $routes = Route::all();
        $customers = Customer::with('route')->get();
        $concepts = ConceptController::listByType(Concept::TYPE_INCOME);

        return view('layouts.sale.index', compact('sales', 'users', 'routes', 'customers', 'concepts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'route_id' => 'nullable|exists:routes,id',
            'customer_id' => 'required|exists:customers,id',
            'cost' => 'required|numeric|min:0',
            'concept_id' => [
                'required',
                ConceptController::conceptExistsRule(Concept::TYPE_INCOME),
            ],
            'carboy_codebars' => 'nullable|array',
            'carboy_codebars.*' => 'nullable|string|max:255',
        ]);

        $sale = Sale::create([
            'user_id' => $validated['user_id'],
            'route_id' => $validated['route_id'] ?? null,
            'customer_id' => $validated['customer_id'],
            'cost' => $validated['cost'],
            'concept_id' => $validated['concept_id'],
            'created_by' => Auth::id(),
            'timestamp' => now(),
        ]);

        $codebars = collect($validated['carboy_codebars'] ?? [])
            ->map(fn ($codebar) => trim((string) $codebar))
            ->filter()
            ->values();

        foreach ($codebars as $codebar) {
            CarboySale::create([
                'sale_id' => $sale->id,
                'carboy_codebar' => $codebar,
                'timestamp' => now(),
            ]);
        }

        return redirect()->route('sales.index')->with('success', 'Entrega/Venta registrada exitosamente');
    }

    public function edit(Sale $sale)
    {
        $sale->load('user', 'route', 'customer', 'concept', 'carboySales');

        $sales = Sale::with('user', 'route', 'customer', 'concept', 'carboySales')
            ->orderBy('timestamp', 'desc')
            ->get()
            ->map(function ($saleItem) {
                $saleItem->carboy_count = $saleItem->carboySales->count();
                return $saleItem;
            });

        $users = User::all();
        $routes = Route::all();
        $customers = Customer::with('route')->get();
        $concepts = ConceptController::listByType(Concept::TYPE_INCOME);

        return view('layouts.sale.index', compact('sale', 'sales', 'users', 'routes', 'customers', 'concepts'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'route_id' => 'nullable|exists:routes,id',
            'customer_id' => 'required|exists:customers,id',
            'cost' => 'required|numeric|min:0',
            'concept_id' => [
                'required',
                ConceptController::conceptExistsRule(Concept::TYPE_INCOME),
            ],
            'carboy_codebars' => 'nullable|array',
            'carboy_codebars.*' => 'nullable|string|max:255',
        ]);

        $sale->update([
            'user_id' => $validated['user_id'],
            'route_id' => $validated['route_id'] ?? null,
            'customer_id' => $validated['customer_id'],
            'cost' => $validated['cost'],
            'concept_id' => $validated['concept_id'],
            'created_by' => Auth::id(),
        ]);

        $sale->carboySales()->delete();

        $codebars = collect($validated['carboy_codebars'] ?? [])
            ->map(fn ($codebar) => trim((string) $codebar))
            ->filter()
            ->values();

        foreach ($codebars as $codebar) {
            CarboySale::create([
                'sale_id' => $sale->id,
                'carboy_codebar' => $codebar,
                'timestamp' => now(),
            ]);
        }

        return redirect()->route('sales.index')->with('success', 'Entrega/Venta actualizada exitosamente');
    }

    public function destroy(Sale $sale)
    {
        $sale->carboySales()->delete();
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Entrega/Venta eliminada exitosamente');
    }
}
