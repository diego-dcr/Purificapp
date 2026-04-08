<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Output;
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
                'error' => 'No se pudo resolver el usuario del output',
            ], 422);
        }

        $route = MobileApiPayload::routeFromRequest($request, $user);
        if (! $route) {
            return response()->json([
                'success' => false,
                'error' => 'No se pudo resolver la ruta del output',
            ], 422);
        }

        $carboyCodes = MobileApiPayload::carboyCodesFromRequest($request);
        if ($carboyCodes === []) {
            return response()->json([
                'success' => false,
                'error' => 'No se recibieron códigos de garrafón para el output',
            ], 422);
        }

        $timestamp = MobileApiPayload::timestampFromRequest($request);
        $externalId = $request->string('id')->trim()->value() ?: null;

        if ($externalId) {
            $existingOutput = Output::query()->where('external_id', $externalId)->first();
            if ($existingOutput) {
                return response()->json([
                    'success' => true,
                    'message' => 'Output previamente registrado',
                    'registro' => [
                        'output_id' => $existingOutput->id,
                        'codigo_output' => $existingOutput->external_id,
                    ],
                ]);
            }
        }

        $output = DB::transaction(function () use ($externalId, $request, $route, $timestamp, $user, $carboyCodes) {
            $output = Output::query()->create([
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
                    'output_id' => $output->id,
                    'carboy_codebar' => $code,
                    'timestamp' => $timestamp,
                ]);
            }

            return $output;
        });

        return response()->json([
            'success' => true,
            'message' => 'Output registrado correctamente',
            'registro' => [
                'output_id' => $output->id,
                'codigo_output' => $output->external_id ?: (string) $output->id,
                'total_garrafones' => count($carboyCodes),
            ],
        ]);
    }
}