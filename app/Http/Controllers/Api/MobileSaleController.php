<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\CarboySale;
use App\Support\MobileApiPayload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobileSaleController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'cliente' => ['nullable', 'string'],
            'codigo_cliente' => ['nullable', 'string'],
            'codigo_barras' => ['nullable', 'string'],
            'no_cliente' => ['nullable'],
            'numero_cliente' => ['nullable'],
            'codigo_usuario' => ['nullable'],
            'codigo_usuario_repartidor' => ['nullable'],
            'codigo_repartidor' => ['nullable'],
            'ruta' => ['nullable', 'string'],
            'codigo_concepto' => ['nullable', 'string'],
            'concepto' => ['nullable', 'string'],
            'no_concepto' => ['nullable', 'string'],
            'monto' => ['nullable', 'numeric'],
            'costo' => ['nullable', 'numeric'],
            'id' => ['nullable', 'string'],
            'latitud' => ['nullable'],
            'longitud' => ['nullable'],
        ]);

        $user = MobileApiPayload::userFromRequest($request);
        if (! $user) {
            return response()->json([
                'success' => false,
                'error' => 'No se pudo resolver el usuario de la entrega',
            ], 422);
        }

        $customer = MobileApiPayload::customerFromRequest($request);
        if (! $customer) {
            return response()->json([
                'success' => false,
                'error' => 'No se pudo resolver el cliente de la entrega',
            ], 422);
        }

        $concept = MobileApiPayload::conceptFromRequest($request);
        if (! $concept) {
            return response()->json([
                'success' => false,
                'error' => 'No se pudo resolver el concepto de la entrega',
            ], 422);
        }

        $route = MobileApiPayload::routeFromRequest($request, $user);
        $timestamp = MobileApiPayload::timestampFromRequest($request);
        $externalId = $request->string('id')->trim()->value() ?: null;
        $carboyCodes = MobileApiPayload::carboyCodesFromRequest($request);
        $cost = (float) ($request->input('monto', $request->input('costo', 0)));

        if ($externalId) {
            $existingSale = Sale::query()->where('external_id', $externalId)->first();
            if ($existingSale) {
                return response()->json([
                    'success' => true,
                    'message' => 'Entrega previamente registrada',
                    'registro' => [
                        'sale_id' => $existingSale->id,
                        'codigo_reparto' => $existingSale->external_id,
                    ],
                ]);
            }
        }

        $sale = DB::transaction(function () use ($concept, $cost, $customer, $externalId, $request, $route, $timestamp, $user, $carboyCodes) {
            $sale = Sale::query()->create([
                'user_id' => $user->id,
                'route_id' => $route?->id,
                'customer_id' => $customer->id,
                'cost' => $cost,
                'concept_id' => $concept->id,
                'created_by' => $user->id,
                'external_id' => $externalId,
                'latitude' => filled($request->input('latitud')) ? (string) $request->input('latitud') : null,
                'longitude' => filled($request->input('longitud')) ? (string) $request->input('longitud') : null,
                'timestamp' => $timestamp,
            ]);

            foreach ($carboyCodes as $code) {
                CarboySale::query()->create([
                    'sale_id' => $sale->id,
                    'carboy_codebar' => $code,
                    'timestamp' => $timestamp,
                ]);
            }

            return $sale;
        });

        return response()->json([
            'success' => true,
            'message' => 'Entrega registrada correctamente',
            'registro' => [
                'sale_id' => $sale->id,
                'codigo_reparto' => $sale->external_id ?: (string) $sale->id,
                'codigo_cliente' => $customer->barcode,
                'total_garrafones' => count($carboyCodes),
            ],
        ]);
    }
}