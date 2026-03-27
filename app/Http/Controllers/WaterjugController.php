<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Waterjug;
use Illuminate\Http\Request;

class WaterjugController extends Controller
{
    public function index()
    {
        $lots = Lot::orderBy('lot_number')->get();
        $waterjugs = Waterjug::with('lot')->orderByDesc('id')->get();

        return view('layouts.waterjug.index', compact('lots', 'waterjugs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|unique:waterjugs,barcode|max:255',
            'conservation_state' => 'required|string|max:255',
            'lot_id' => 'required|exists:lots,id',
            'status' => 'required|in:En_planta,En_ruta,Con_cliente,Retornado,Perdido,Mantenimiento,Retirado',
            'timestamp' => 'nullable|date',
        ]);

        $validated['timestamp'] = $validated['timestamp'] ?? now();

        Waterjug::create($validated);

        return redirect()->route('waterjugs.index')->with('success', 'Garrafón creado exitosamente');
    }

    public function edit(Waterjug $waterjug)
    {
        $lots = Lot::orderBy('lot_number')->get();
        $waterjugs = Waterjug::with('lot')->orderByDesc('id')->get();

        return view('layouts.waterjug.index', compact('lots', 'waterjugs', 'waterjug'));
    }

    public function update(Request $request, Waterjug $waterjug)
    {
        $validated = $request->validate([
            'barcode' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('waterjugs', 'barcode')->ignore($waterjug->id),
            ],
            'conservation_state' => 'required|string|max:255',
            'lot_id' => 'required|exists:lots,id',
            'status' => 'required|in:En_planta,En_ruta,Con_cliente,Retornado,Perdido,Mantenimiento,Retirado',
            'timestamp' => 'nullable|date',
        ]);

        $validated['timestamp'] = $validated['timestamp'] ?? now();

        $waterjug->update($validated);

        return redirect()->route('waterjugs.index')->with('success', 'Garrafón actualizado exitosamente');
    }

    public function destroy(Waterjug $waterjug)
    {
        $waterjug->delete();

        return redirect()->route('waterjugs.index')->with('success', 'Garrafón eliminado exitosamente');
    }
}
