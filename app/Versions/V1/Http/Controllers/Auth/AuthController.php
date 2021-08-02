<?php

namespace App\Versions\V1\Http\Controllers\Auth;

use App\Versions\V1\DTO\LoginInputDTO;
use App\Versions\V1\DTO\PasswordTokenDTO;
use App\Versions\V1\Factories\PasswordTokenFactory;
use App\Versions\V1\Http\Requests\Auth\LoginRequest;
use App\Versions\V1\Http\Resources\Auth\OAuthTokenResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\Exceptions\OAuthServerException;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;

class AuthController
{
    use HandlesOAuthErrors;

    /**
     * @throws OAuthServerException
     */
    public function login(LoginRequest $request, PasswordTokenFactory $factory): OAuthTokenResource
    {
        $tokenArray = $this->withErrorHandling(
            fn() => $factory->make(LoginInputDTO::factory()->fromLoginRequest($request))
        );

        return new OAuthTokenResource(PasswordTokenDTO::factory()->fromArray($tokenArray));
    }

    public function logout(Request $request,
                           TokenRepository $tokenRepository,
                           RefreshTokenRepository $refreshTokenRepository
    ): Response {
        $token = $request->bearerToken();
        $tokenRepository->revokeAccessToken($token);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token);

        return response()->noContent();
    }
}
