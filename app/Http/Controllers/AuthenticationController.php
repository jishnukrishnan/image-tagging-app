<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Http\Requests\CreateTokenRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    /**
     * Register user with the input field and return personal access token
     * @param UserRegistrationRequest $request registration request
     * @return JsonResponse access token
     * @throws \Exception e
     */
    public function register(UserRegistrationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $user = User::create($validated);
        } catch (\Exception $e) {
            if ($e->getCode() === "23000") {
                return $this->createEmailAlreadyRegisteredResponse($e, $validated['email']);
            }
            throw $e;
        }
        return response()->json([
            'access_token' => $user->createToken('auth_token')->plainTextToken
        ], 201);
    }

    /**
     * Created user already registered error response.
     * @param \Exception $e exception thrown
     * @param String $email attempted email
     * @return JsonResponse response
     */
    private function createEmailAlreadyRegisteredResponse(\Exception $e, string $email): JsonResponse
    {
        $json = [
            'error_code' => $e->getCode(),
            'error_message' => "User with email '" . $email . "' already registered"
        ];
        return response()->json($json, 400);
    }

    /**
     * Check the given credentials are valid and return a new token.
     * @param CreateTokenRequest $request token request
     * @return Response access token
     * @throws AuthenticationException authentication attempt failed
     */
    public function tokens(CreateTokenRequest $request): Response
    {
        $validated = $request->validated();
        if (!Auth::attempt($validated)) {
            throw new AuthenticationException();
        }

        return response()->json([
            'access_token' => auth()->user()->createToken('auth_token')->plainTextToken
        ]);
    }
}
