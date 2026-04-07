<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Carboy;
use Illuminate\Http\Request;

class CarboyController extends Controller
{
    public function index()
    {
        $lots = Lot::orderBy('lot_number')->get();
        $carboys = Carboy::with('lot')->orderByDesc('id')->get();

        return view('layouts.carboy.index', compact('lots', 'carboys'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|unique:carboys,barcode|max:255',
            'conservation_state' => 'required|string|max:255',
            'lot_id' => 'required|exists:lots,id',
            'status' => 'required|in:En_planta,En_ruta,Con_cliente,Retornado,Perdido,Mantenimiento,Retirado',
            'timestamp' => 'nullable|date',
        ]);

        $validated['timestamp'] = $validated['timestamp'] ?? now();

        Carboy::create($validated);

        return redirect()->route('carboys.index')->with('success', 'Garrafón creado exitosamente');
    }

    public function edit(Carboy $carboy)
    {
        $lots = Lot::orderBy('lot_number')->get();
        $carboys = Carboy::with('lot')->orderByDesc('id')->get();

        return view('layouts.carboy.index', compact('lots', 'carboys', 'carboy'));
    }

    public function update(Request $request, Carboy $carboy)
    {
        $validated = $request->validate([
            'barcode' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('carboys', 'barcode')->ignore($carboy->id),
            ],
            'conservation_state' => 'required|string|max:255',
            'lot_id' => 'required|exists:lots,id',
            'status' => 'required|in:En_planta,En_ruta,Con_cliente,Retornado,Perdido,Mantenimiento,Retirado',
            'timestamp' => 'nullable|date',
        ]);

        $validated['timestamp'] = $validated['timestamp'] ?? now();

        $carboy->update($validated);

        return redirect()->route('carboys.index')->with('success', 'Garrafón actualizado exitosamente');
    }

    public function destroy(Carboy $carboy)
    {
        $carboy->delete();

        return redirect()->route('carboys.index')->with('success', 'Garrafón eliminado exitosamente');
    }
}
