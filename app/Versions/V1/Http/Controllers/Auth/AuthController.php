<?php

namespace App\Versions\V1\Http\Controllers\Auth;

use App\Enums\GrantTypeEnum;
use App\Versions\V1\DTO\LoginInputDTO;
use App\Versions\V1\DTO\PasswordTokenDTO;
use App\Versions\V1\DTO\RefreshInputDTO;
use App\Versions\V1\DTO\UserDTO;
use App\Versions\V1\Facades\OAuth;
use App\Versions\V1\Http\Requests\Auth\LoginRequest;
use App\Versions\V1\Http\Requests\Auth\RefreshTokenRequest;
use App\Versions\V1\Http\Requests\Auth\RegisterRequest;
use App\Versions\V1\Http\Resources\Auth\OAuthTokenResource;
use App\Versions\V1\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\Exceptions\OAuthServerException;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class AuthController
{
    use HandlesOAuthErrors;

    public function register(RegisterRequest $request, UserService $userService): Response
    {
        $userService->register(UserDTO::factory()->fromRegisterRequest($request));

        return response()->noContent(Response::HTTP_CREATED);
    }

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
     * @throws OAuthServerException|UnknownProperties
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
