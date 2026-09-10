<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Connexion avec session (cookie) via le garde "web".
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::guard('web')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return response()->json([
                'message' => 'Ces identifiants ne correspondent à aucun compte.',
                'errors' => ['email' => ['Ces identifiants ne correspondent à aucun compte.']],
            ], 422);
        }

        $request->session()->regenerate();

        return response()->json([
            'data' => ['user' => $request->user()],
        ]);
    }

    /**
     * Utilisateur connecté.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => ['user' => $request->user()],
        ]);
    }

    /**
     * Déconnexion : invalide la session.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Déconnecté avec succès.']);
    }
}