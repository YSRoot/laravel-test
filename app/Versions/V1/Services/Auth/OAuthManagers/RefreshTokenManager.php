<?php

namespace App\Versions\V1\Services\Auth\TokenManagers;

use App\Versions\V1\Enums\GrantTypeEnum;

class RefreshTokenManager extends PasswordTokenManager
{
    protected static string $grantType = GrantTypeEnum::REFRESH_TOKEN;
}
