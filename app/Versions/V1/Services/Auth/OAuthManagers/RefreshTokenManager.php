<?php

namespace App\Versions\V1\Services\Auth\OAuthManagers;

use App\Enums\GrantTypeEnum;

class RefreshTokenManager extends BaseOAuthManager
{
    protected static string $grantType = GrantTypeEnum::REFRESH_TOKEN;
}
