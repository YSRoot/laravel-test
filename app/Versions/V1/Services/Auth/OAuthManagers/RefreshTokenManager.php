<?php

namespace App\Versions\V1\Services\Auth\OAuthManagers;

use App\Enums\GrantTypeEnum;

class RefreshTokenManager extends PasswordTokenManager
{
    protected static string $grantType = GrantTypeEnum::REFRESH_TOKEN;
}
