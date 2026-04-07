<?php

namespace App\Support;

use App\Models\Concept;
use App\Models\Customer;
use App\Models\Route;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MobileApiPayload
{
    public static function userFromRequest(Request $request): ?User
    {
        $candidates = array_values(array_filter([
            $request->input('codigo_usuario'),
            $request->input('codigo_usuario_repartidor'),
            $request->input('codigo_repartidor'),
            $request->input('user_id'),
            $request->input('usuario'),
            $request->input('username'),
        ], fn ($value) => filled($value)));

        foreach ($candidates as $candidate) {
            $normalized = trim((string) $candidate);
            if ($normalized === '') {
                continue;
            }

            $user = ctype_digit($normalized)
                ? User::query()->find((int) $normalized)
                : User::query()->where('username', $normalized)->first();

            if ($user) {
                return $user;
            }
        }

        return null;
    }

    public static function routeFromRequest(Request $request, ?User $user = null): ?Route
    {
        $routeValue = trim((string) $request->input('ruta', ''));

        if ($routeValue !== '') {
            $route = Route::query()
                ->whereRaw('LOWER(code) = ?', [Str::lower($routeValue)])
                ->orWhereRaw('LOWER(name) = ?', [Str::lower($routeValue)])
                ->first();

            if ($route) {
                return $route;
            }
        }

        if ($user) {
            return $user->assignedRoute()->first();
        }

        return null;
    }

    public static function customerFromRequest(Request $request): ?Customer
    {
        $barcodeCandidates = array_values(array_filter([
            $request->input('codigo_cliente'),
            $request->input('codigo_barras'),
            $request->input('client_code'),
        ], fn ($value) => filled($value)));

        foreach ($barcodeCandidates as $candidate) {
            $normalized = self::normalizeCode($candidate);
            if ($normalized === '') {
                continue;
            }

            $customer = Customer::query()->where('barcode', $normalized)->first();
            if ($customer) {
                return $customer;
            }
        }

        $numberCandidates = array_values(array_filter([
            $request->input('no_cliente'),
            $request->input('numero_cliente'),
            $request->input('client_number'),
        ], fn ($value) => filled($value)));

        foreach ($numberCandidates as $candidate) {
            $normalized = self::normalizeCode($candidate);
            if ($normalized === '') {
                continue;
            }

            $customer = Customer::query()->where('number', $normalized)->first();
            if ($customer) {
                return $customer;
            }
        }

        return null;
    }

    public static function conceptFromRequest(Request $request): ?Concept
    {
        $codeCandidates = array_values(array_filter([
            $request->input('codigo_concepto'),
            self::extractConceptCode($request->input('no_concepto')),
            self::extractConceptCode($request->input('concepto')),
        ], fn ($value) => filled($value)));

        foreach ($codeCandidates as $candidate) {
            $normalized = trim((string) $candidate);
            if ($normalized === '') {
                continue;
            }

            $concept = Concept::query()->where('code', $normalized)->first();
            if ($concept) {
                return $concept;
            }
        }

        $nameCandidates = array_values(array_filter([
            self::extractConceptName($request->input('concepto')),
            self::extractConceptName($request->input('no_concepto')),
        ], fn ($value) => filled($value)));

        foreach ($nameCandidates as $candidate) {
            $normalized = Str::lower(trim((string) $candidate));
            if ($normalized === '') {
                continue;
            }

            $concept = Concept::query()->whereRaw('LOWER(name) = ?', [$normalized])->first();
            if ($concept) {
                return $concept;
            }
        }

        return null;
    }

    public static function carboyCodesFromRequest(Request $request): array
    {
        $codes = [];

        $explicitCodes = $request->input('codigos_garrafones', []);
        if (is_array($explicitCodes)) {
            $codes = array_merge($codes, $explicitCodes);
        }

        $singleCode = $request->input('codigo_barras');
        if (is_string($singleCode) && trim($singleCode) !== '') {
            $codes[] = $singleCode;
        }

        $sales = $request->input('ventas', []);
        if (is_array($sales)) {
            foreach ($sales as $sale) {
                if (! is_array($sale)) {
                    continue;
                }

                $value = $sale['codigo_barras'] ?? null;
                if (is_string($value) && trim($value) !== '') {
                    $codes[] = $value;
                }
            }
        }

        return array_values(array_unique(array_filter(array_map(
            fn ($code) => trim((string) $code),
            $codes,
        ))));
    }

    public static function timestampFromRequest(Request $request): Carbon
    {
        $timestamp = $request->input('timestamp')
            ?? $request->input('fecha_registro')
            ?? $request->input('fecha');

        if (! filled($timestamp)) {
            return now();
        }

        try {
            return Carbon::parse((string) $timestamp);
        } catch (\Throwable) {
            return now();
        }
    }

    public static function normalizeCode(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        return preg_replace('/\.0+$/', '', preg_replace('/\s+/', '', trim((string) $value))) ?? '';
    }

    public static function extractConceptCode(mixed $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        $text = trim((string) $value);
        if ($text === '') {
            return null;
        }

        if (preg_match('/^([^-\s]+)/', $text, $matches) === 1) {
            return $matches[1];
        }

        return $text;
    }

    public static function extractConceptName(mixed $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        $text = trim((string) $value);
        if ($text === '') {
            return null;
        }

        $parts = explode('-', $text, 2);
        return trim($parts[count($parts) - 1]);
    }
}