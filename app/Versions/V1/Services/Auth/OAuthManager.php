<?php

namespace App\Versions\V1\Services\Auth;

use App\Enums\GrantTypeEnum;
use App\Versions\V1\Services\Auth\OAuthManagers\PasswordTokenManager;
use App\Versions\V1\Services\Auth\OAuthManagers\RefreshTokenManager;
use App\Versions\V1\Services\Auth\OAuthManagers\TokenManagerInterface;
use Illuminate\Support\Manager;

class OAuthManager extends Manager
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
