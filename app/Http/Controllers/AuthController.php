<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        try {
            $token = Auth::guard('api')->login($user);

            if (! $token) {
                throw new JWTException('Could not create a new token');
            }

            return $this->successResponse(
                $this->tokenPayload($user, $token), 'User registered successfully',
                201
            );
        } catch (JWTException $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }

    }

    public function tokenPayload(User $user, string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => new UserResource($user),
        ];
    }
}
