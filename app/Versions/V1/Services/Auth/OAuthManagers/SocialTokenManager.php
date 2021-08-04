<?php

namespace App\Versions\V1\Services\Auth\OAuthManagers;

use App\Enums\GrantTypeEnum;

class SocialTokenManager extends PasswordTokenManager
{
    protected static string $grantType = GrantTypeEnum::SOCIAL;
}
