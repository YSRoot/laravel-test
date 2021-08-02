<?php

namespace App\Versions\V1\DTO;

interface OAuthAuthorizeContract
{
    public function authorizeParams(): array;
}
