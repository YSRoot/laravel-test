<?php

namespace App\Versions\V1\DTO\Contracts;

interface OAuthAuthorizeContract
{
    public function authorizeParams(): array;
}
