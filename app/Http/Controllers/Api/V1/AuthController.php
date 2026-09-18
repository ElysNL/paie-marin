<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Connexion avec session (cookie) via le garde "web".
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user && $user->isLocked()) {
            return response()->json([
                'message' => 'Compte temporairement verrouillé. Réessayez dans ' . $user->locked_until->diffForHumans(),
            ], 423);
        }

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            if ($user) {
                $attempts = $user->increment('failed_login_attempts');
                if ($attempts >= 5) {
                    $user->update(['locked_until' => now()->addMinutes(15)]);
                }
            }
            return response()->json(['message' => 'Identifiants incorrects.'], 401);
        }

        $user->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);

        auth()->login($user);

        // Stocker le hash du mot de passe en session pour détecter les changements
        $request->session()->put('password_hash', $user->password);

        return response()->json([
            'message' => 'Connexion réussie.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'actif' => $user->actif,
            ],
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