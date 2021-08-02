<?php

namespace App\Versions\V1\Services\Auth;

use App\Versions\V1\DTO\BaseDTO;
use App\Versions\V1\DTO\OAuthAuthorizeContract;
use App\Versions\V1\Enums\GrantTypeEnum;
use App\Versions\V1\Services\Auth\TokenManagers\PasswordTokenManager;
use App\Versions\V1\Services\Auth\TokenManagers\RefreshTokenManager;
use App\Versions\V1\Services\Auth\TokenManagers\TokenManagerInterface;
use Illuminate\Support\Manager;

/**
 * @method array make(OAuthAuthorizeContract $dto)
 */
class TokenManager extends Manager
{
    public function createPasswordDriver(): TokenManagerInterface
    {
        return new PasswordTokenManager();
    }

    public function createRefreshTokenDriver(): TokenManagerInterface
    {
        return new RefreshTokenManager();
    }

    public function getDefaultDriver(): string
    {
        return GrantTypeEnum::PASSWORD;
    }
}
