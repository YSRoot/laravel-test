<?php

namespace App\Versions\V1\Http\Controllers\Auth;

use App\Versions\V1\DTO\LoginInputDTO;
use App\Versions\V1\DTO\PasswordTokenDTO;
use App\Versions\V1\DTO\RefreshInputDTO;
use App\Versions\V1\Enums\GrantTypeEnum;
use App\Versions\V1\Facades\OAuth;
use App\Versions\V1\Http\Requests\Auth\LoginRequest;
use App\Versions\V1\Http\Requests\Auth\RefreshTokenRequest;
use App\Versions\V1\Http\Resources\Auth\OAuthTokenResource;
use App\Versions\V1\Services\Auth\OAuthManager;
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
    public function login(LoginRequest $request): OAuthTokenResource
    {
        $tokenArray = $this->withErrorHandling(
            fn() => OAuth::make(LoginInputDTO::factory()->fromLoginRequest($request))
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

    /**
     * @throws OAuthServerException
     */
    public function refresh(RefreshTokenRequest $request): OAuthTokenResource
    {
        $tokenArray = $this->withErrorHandling(
            fn() => OAuth::driver(GrantTypeEnum::REFRESH_TOKEN)
                ->make(RefreshInputDTO::factory()->fromRefreshTokenRequest($request))
        );

        return new OAuthTokenResource(PasswordTokenDTO::factory()->fromArray($tokenArray));
    }
}
