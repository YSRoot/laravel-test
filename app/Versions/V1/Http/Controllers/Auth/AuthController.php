<?php

namespace App\Versions\V1\Http\Controllers\Auth;

use App\Versions\V1\DTO\LoginInputDTO;
use App\Versions\V1\DTO\PasswordTokenDTO;
use App\Versions\V1\Factories\PasswordTokenFactory;
use App\Versions\V1\Http\Requests\Auth\LoginRequest;
use App\Versions\V1\Http\Resources\Auth\OAuthTokenResource;
use Laravel\Passport\Exceptions\OAuthServerException;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;

class AuthController
{
    use HandlesOAuthErrors;

    /**
     * @throws OAuthServerException
     */
    public function login(LoginRequest $request, PasswordTokenFactory $factory): OAuthTokenResource
    {
        $tokenArray = $this->withErrorHandling(fn() => $factory->make(LoginInputDTO::factory()->fromLoginRequest($request)));

        return new OAuthTokenResource(PasswordTokenDTO::factory()->fromArray($tokenArray));
    }
}
