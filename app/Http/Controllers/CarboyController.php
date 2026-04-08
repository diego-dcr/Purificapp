<?php

namespace App\Http\Controllers;

use App\Models\CarboyOutput;
use App\Models\CarboySale;
use App\Models\Carboy;
use App\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CarboyController extends Controller
{
    public function index()
    {
        $traceCode = trim((string) request('trace_code', ''));
        $lots = Lot::orderBy('lot_number')->get();
        $carboys = Carboy::with('lot')->orderByDesc('id')->get();

        $carboyHistory = collect();

        if ($traceCode !== '') {
            $carboySalesBarcodeColumn = Schema::hasColumn('carboy_sales', 'carboy_codebar')
                ? 'carboy_codebar'
                : 'carboy_barcode';

            $carboyOutputsBarcodeColumn = Schema::hasColumn('carboy_outputs', 'carboy_codebar')
                ? 'carboy_codebar'
                : 'carboy_barcode';

            $salesHistory = CarboySale::with(['sale.user', 'sale.customer', 'sale.route'])
                ->where($carboySalesBarcodeColumn, $traceCode)
                ->get()
                ->map(function (CarboySale $entry) {
                    return [
                        'movement_type' => 'Venta',
                        'movement_id' => $entry->sale_id,
                        'timestamp' => $entry->timestamp ?? $entry->sale?->timestamp,
                        'user_name' => $entry->sale?->user?->name,
                        'route_name' => $entry->sale?->route?->name,
                        'customer_name' => $entry->sale?->customer?->name,
                    ];
                });

            $returnsHistory = CarboyOutput::with(['output.user', 'output.route'])
                ->where($carboyOutputsBarcodeColumn, $traceCode)
                ->get()
                ->map(function (CarboyOutput $entry) {
                    return [
                        'movement_type' => 'Retorno',
                        'movement_id' => $entry->output_id,
                        'timestamp' => $entry->timestamp ?? $entry->output?->timestamp,
                        'user_name' => $entry->output?->user?->name,
                        'route_name' => $entry->output?->route?->name,
                        'customer_name' => null,
                    ];
                });

            $carboyHistory = $salesHistory
                ->concat($returnsHistory)
                ->sortByDesc('timestamp')
                ->values();
        }

        return view('layouts.carboy.index', compact('lots', 'carboys', 'traceCode', 'carboyHistory'));
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
