<?php

namespace App\Versions\V1\Services\Auth\OAuthManagers;

use App\Enums\GrantTypeEnum;

class SocialTokenManager extends BaseOAuthManager
{
    protected static string $grantType = GrantTypeEnum::SOCIAL;
}
