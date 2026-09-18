<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            if ($user->isLocked()) {
                auth()->guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return response()->json([
                    'message' => 'Compte temporairement verrouillé. Réessayez plus tard.',
                ], 423);
            }

            // Invalider la session si le mot de passe a changé depuis la connexion
            $sessionPasswordHash = $request->session()->get('password_hash');
            if ($sessionPasswordHash && $sessionPasswordHash !== $user->password) {
                auth()->guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return response()->json([
                    'message' => 'Session expirée. Veuillez vous reconnecter.',
                ], 401);
            }
        }

        return $next($request);
    }
}
