<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Retorno;
use App\Models\CarboyOutput;
use App\Support\MobileApiPayload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobileRetornoController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'codigo_usuario' => ['nullable'],
            'ruta' => ['required', 'string'],
            'no_ruta' => ['nullable', 'string'],
            'id' => ['nullable', 'string'],
            'latitud' => ['nullable'],
            'longitud' => ['nullable'],
        ]);

        $user = MobileApiPayload::userFromRequest($request);
        if (! $user) {
            return response()->json([
                'success' => false,
                'error' => 'No se pudo resolver el usuario del retorno',
            ], 422);
        }

        $route = MobileApiPayload::routeFromRequest($request, $user);
        if (! $route) {
            return response()->json([
                'success' => false,
                'error' => 'No se pudo resolver la ruta del retorno',
            ], 422);
        }

        $carboyCodes = MobileApiPayload::carboyCodesFromRequest($request);
        if ($carboyCodes === []) {
            return response()->json([
                'success' => false,
                'error' => 'No se recibieron códigos de garrafón para el retorno',
            ], 422);
        }

        $timestamp = MobileApiPayload::timestampFromRequest($request);
        $externalId = $request->string('id')->trim()->value() ?: null;

        if ($externalId) {
            $existingRetorno = Retorno::query()->where('external_id', $externalId)->first();
            if ($existingRetorno) {
                return response()->json([
                    'success' => true,
                    'message' => 'Retorno previamente registrado',
                    'registro' => [
                        'retorno_id' => $existingRetorno->id,
                        'codigo_retorno' => $existingRetorno->external_id,
                    ],
                ]);
            }
        }

        $retorno = DB::transaction(function () use ($externalId, $request, $route, $timestamp, $user, $carboyCodes) {
            $retorno = Retorno::query()->create([
                'user_id' => $user->id,
                'route_id' => $route->id,
                'created_by' => $user->id,
                'external_id' => $externalId,
                'latitude' => filled($request->input('latitud')) ? (string) $request->input('latitud') : null,
                'longitude' => filled($request->input('longitud')) ? (string) $request->input('longitud') : null,
                'timestamp' => $timestamp,
            ]);

            foreach ($carboyCodes as $code) {
                CarboyOutput::query()->create([
                    'retorno_id' => $retorno->id,
                    'carboy_codebar' => $code,
                    'timestamp' => $timestamp,
                ]);
            }

            return $retorno;
        });

        return response()->json([
            'success' => true,
            'message' => 'Retorno registrado correctamente',
            'registro' => [
                'retorno_id' => $retorno->id,
                'codigo_retorno' => $retorno->external_id ?: (string) $retorno->id,
                'total_garrafones' => count($carboyCodes),
            ],
        ]);
    }
}