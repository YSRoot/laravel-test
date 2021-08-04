<?php

namespace App\Versions\V1\Bridge;

use App\Enums\GrantTypeEnum;
use App\Versions\V1\DTO\PasswordTokenDTO;
use App\Versions\V1\DTO\SocialAuthorizeDTO;
use App\Versions\V1\Facades\OAuth;
use App\Versions\V1\Http\Requests\Auth\SocialiteRedirectRequest;
use App\Versions\V1\Services\SocialiteService;
use Illuminate\Http\Request;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Laravel\Socialite\Facades\Socialite as LaravelSocialite;
use Throwable;

class Socialite
{
    public function __construct(
        private SocialiteService $socialiteService
    ) {
    }

    private const CLIENT_PARAMS_SESSION_KEY = 'client_params';

    public function redirect(SocialiteRedirectRequest $request, string $driver): RedirectResponse
    {
        $request->session()->put(
            self::CLIENT_PARAMS_SESSION_KEY,
            [$request->client_id, $request->client_secret, $request->scope]
        );

        return LaravelSocialite::driver($driver)->redirect();
    }

    /**
     * @throws UnknownProperties
     * @throws InvalidStateException
     * @throws Throwable
     */
    public function callback(Request $request, string $driver): PasswordTokenDTO
    {
        /** @var User $socialiteUser */
        $socialiteUser = LaravelSocialite::driver($driver)->user();

        //check session has client params
        throw_if(!$request->session()->has(self::CLIENT_PARAMS_SESSION_KEY), InvalidStateException::class);
        //get client params
        [$clientId, $clientSecret, $scope] = $request->session()->get(self::CLIENT_PARAMS_SESSION_KEY);

        //create user or social profile
        $listener = $this->socialiteService->handleCallback($socialiteUser, $driver, $clientId);

        //authorize user with social grant
        $tokenArray = OAuth::driver(GrantTypeEnum::SOCIAL)->make(
            new SocialAuthorizeDTO(
                driver: $driver,
                accessToken: $socialiteUser->token,
                clientId: $clientId,
                clientSecret: $clientSecret,
                scope: $scope,
            )
        );

        //revoke access token
        $listener->revokeAccessToken($socialiteUser->token);

        return PasswordTokenDTO::factory()->fromArray($tokenArray);
    }
}
