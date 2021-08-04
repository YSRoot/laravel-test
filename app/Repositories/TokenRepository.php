<?php

namespace App\Repositories;

use Carbon\Carbon;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use Laravel\Passport\TokenRepository as BaseTokenRepository;

class TokenRepository extends BaseTokenRepository
{
    /**
     * Find a valid token for the given ID and client.
     */
    public function findValidTokenForIdAndClientId(string $id, string $clientId): ?Token
    {
        return Passport::token()
            ->where('client_id', $clientId)
            ->where('id', $id)
            ->where('revoked', 0)
            ->where('expires_at', '>', Carbon::now())
            ->latest('expires_at')
            ->first();
    }
}
