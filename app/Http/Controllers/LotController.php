<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use Illuminate\Http\Request;

class LotController extends Controller
{
    public function index()
    {
        $lots = Lot::withCount('carboys')->orderByDesc('id')->get();

        return view('layouts.lot.index', compact('lots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lot_number' => 'required|string|unique:lots,lot_number|max:255',
            'supplier' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'observations' => 'nullable|string|max:255',
            'production_date' => 'nullable|date',
            'expiration_date' => 'nullable|date|after_or_equal:production_date',
        ]);

        Lot::create($validated);

        return redirect()->route('lots.index')->with('success', 'Lote creado exitosamente');
    }

    public function edit(Lot $lot)
    {
        $lots = Lot::withCount('carboys')->orderByDesc('id')->get();

        return view('layouts.lot.index', compact('lots', 'lot'));
    }

    public function update(Request $request, Lot $lot)
    {
        $validated = $request->validate([
            'lot_number' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('lots', 'lot_number')->ignore($lot->id),
            ],
            'supplier' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'observations' => 'nullable|string|max:255',
            'production_date' => 'nullable|date',
            'expiration_date' => 'nullable|date|after_or_equal:production_date',
        ]);

        $lot->update($validated);

        return redirect()->route('lots.index')->with('success', 'Lote actualizado exitosamente');
    }

    public function destroy(Lot $lot)
    {
        $lot->delete();

        return redirect()->route('lots.index')->with('success', 'Lote eliminado exitosamente');
    }
}
