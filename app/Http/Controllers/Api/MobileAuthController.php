<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MobileAuthController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $username = trim($validated['username']);

        $user = User::query()
            ->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'authenticated' => false,
                'error' => 'Credenciales incorrectas',
            ], 401);
        }

        $route = $user->assignedRoute()->first();

        return response()->json([
            'success' => true,
            'authenticated' => true,
            'message' => 'Usuario autenticado correctamente',
            'user' => [
                'codigo' => (string) $user->id,
                'usuario' => $user->username ?: $user->email,
                'nombre' => $user->name,
                'rol' => $user->getRoleNames()->first() ?: 'operation',
                'ruta' => $route?->code ?: '',
                'rowNumber' => $user->id,
            ],
        ]);
    }
}