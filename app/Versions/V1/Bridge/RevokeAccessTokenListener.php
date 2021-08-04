<?php

namespace App\Versions\V1\Bridge;

interface RevokeAccessTokenListener
{
    public function revokeAccessToken(string $tokenId): void;
}
