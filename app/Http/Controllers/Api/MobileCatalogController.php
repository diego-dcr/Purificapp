<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\Customer;
use App\Models\Route;
use Illuminate\Http\JsonResponse;

class MobileCatalogController extends Controller
{
    public function concepts(): JsonResponse
    {
        $concepts = Concept::query()
            ->orderBy('code')
            ->get()
            ->map(fn (Concept $concept) => [
                'id' => $concept->id,
                'codigo' => $concept->code,
                'nombre' => $concept->name,
                'descripcion' => $concept->name,
            ])
            ->values();

        return response()->json([
            'success' => true,
            'data' => $concepts,
            'total' => $concepts->count(),
            'message' => 'Conceptos obtenidos exitosamente',
        ]);
    }

    public function customers(): JsonResponse
    {
        $customers = Customer::query()
            ->with('route')
            ->orderBy('name')
            ->get()
            ->map(fn (Customer $customer) => [
                'id' => $customer->id,
                'codigo_barras' => $customer->barcode,
                'no_cliente' => $customer->number,
                'nombre_cliente' => $customer->name,
                'nombre' => $customer->name,
                'ruta' => $customer->route?->code ?: '',
                'ruta_asignada' => $customer->route?->code ?: '',
                'direccion' => '',
                'telefono' => '',
            ])
            ->values();

        return response()->json([
            'success' => true,
            'data' => $customers,
            'total' => $customers->count(),
            'message' => 'Clientes obtenidos exitosamente',
        ]);
    }

    public function routes(): JsonResponse
    {
        $routes = Route::query()
            ->with('user')
            ->orderBy('code')
            ->orderBy('name')
            ->get()
            ->map(fn (Route $route) => [
                'ruta' => $route->code ?: $route->name,
                'codigo_repartidor' => (string) ($route->user?->id ?? ''),
                'usuario' => $route->user?->username ?: '',
                'nombre' => $route->name,
                'rol' => $route->user?->getRoleNames()->first() ?: '',
            ])
            ->values();

        return response()->json([
            'success' => true,
            'data' => $routes,
            'total' => $routes->count(),
            'message' => 'Rutas obtenidas exitosamente',
        ]);
    }
}