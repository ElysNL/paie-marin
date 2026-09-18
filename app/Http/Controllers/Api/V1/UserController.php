<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(20);

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);
        $validated = $request->validated();
        // Cast 'hashed' sur User applique Hash::make() automatiquement
        $validated['email_verified_at'] = now();

        $user = User::create($validated);

        return (new UserResource($user->fresh()))->response()->setStatusCode(201);
    }

    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);
        return response()->json(new UserResource($user));
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);
        $validated = $request->validated();

        if (empty($validated['password'])) {
            unset($validated['password']);
        }
        // password reste dans validated si non vide → cast 'hashed' le hash

        $user->update($validated);

        return response()->json(new UserResource($user->fresh()));
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json(null, 204);
    }

    public function toggleActif(User $user): JsonResponse
    {
        $this->authorize('toggleActif', $user);
        $user->update(['actif' => !$user->actif]);
        return response()->json(new UserResource($user->fresh()));
    }
}
