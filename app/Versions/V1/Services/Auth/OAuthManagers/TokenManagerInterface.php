<?php

namespace App\Versions\V1\Services\Auth\OAuthManagers;

use App\Versions\V1\DTO\Contracts\OAuthAuthorizeContract;

interface TokenManagerInterface
{
    public function make(OAuthAuthorizeContract $dto): array;
}
