<?php

namespace App\Providers;

use Adaojunior\PassportSocialGrant\SocialGrantException;
use Adaojunior\PassportSocialGrant\SocialGrantUserProvider as BaseGrantProvider;
use App\Models\User;
use App\Repositories\TokenRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Throwable;

class SocialGrantUserProvider implements BaseGrantProvider
{
    public function __construct(
        private TokenRepository $tokenRepository,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function getUserByAccessToken(string $provider,
                                         string $accessToken,
                                         ClientEntityInterface $client
    ): ?Authenticatable {
        $token = $this->tokenRepository->findValidTokenForIdAndClientId($accessToken, $client->getIdentifier());

        /** @var User $user */
        throw_if(!$token && !$user = $token->user, SocialGrantException::invalidAccessToken());

        throw_if(!$user->socialProfiles()->where('driver', $provider)->exists(), SocialGrantException::invalidProvider());

        return $user;
    }
}
