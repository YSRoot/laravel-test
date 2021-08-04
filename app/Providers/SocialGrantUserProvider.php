<?php

namespace App\Providers;

use Adaojunior\PassportSocialGrant\SocialGrantException;
use Adaojunior\PassportSocialGrant\SocialGrantUserProvider as BaseGrantProvider;
use App\Enums\SocialiteDriverEnum;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use RuntimeException;
use Throwable;

class SocialGrantUserProvider implements BaseGrantProvider
{
    /**
     * @throws Throwable
     */
    public function getUserByAccessToken(string $provider,
                                         string $accessToken,
                                         ClientEntityInterface $client
    ): Authenticatable {
        $clientProvider = $client->provider ?: config('auth.guards.api.provider');

        if (is_null($model = config('auth.providers.' . $clientProvider . '.model'))) {
            throw new RuntimeException('Unable to determine authentication model from configuration.');
        }

        $this->validateProvider($provider);

        try {
            /** @var User $socialUser */
            $socialUser = Socialite::driver($provider)->userFromToken($accessToken);
        } catch (Throwable) {
            throw SocialGrantException::invalidAccessToken();
        }

        return $model::query()
            ->whereHas('socialProfiles', function (Builder $query) use ($provider, $socialUser) {
                $query
                    ->where('driver', $provider)
                    ->where('driver_id', $socialUser->getId());
            })
            /** User not found that was unexpected */
            ->firstOr(fn() => throw OAuthServerException::serverError('An unexpected error has occurred'));
    }

    /**
     * @throws SocialGrantException
     */
    private function validateProvider(string $provider): void
    {
        if (!in_array(strtolower($provider), $this->getValidProviders(), true)) {
            throw SocialGrantException::invalidProvider();
        }
    }

    private function getValidProviders(): array
    {
        return [
            SocialiteDriverEnum::GOOGLE,
        ];
    }
}
