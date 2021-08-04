<?php

namespace App\Versions\V1\Services;

use App\Models\UserSocialProfile;
use App\Repositories\TokenRepository;
use App\Versions\V1\Bridge\RevokeAccessTokenListener;
use App\Versions\V1\DTO\UserDTO;
use App\Versions\V1\DTO\UserSocialProfileDTO;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Two\User as SocialiteUser;
use App\Models\User;
use Throwable;

class SocialiteService implements RevokeAccessTokenListener
{
    public function __construct(
        private UserService $userService,
        private TokenRepository $tokenRepository,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function handleCallback(SocialiteUser $socialUser, string $driver, string $clientId): RevokeAccessTokenListener
    {
        $userDTO = UserDTO::factory()->fromSocialUser($socialUser);
        $user = $this->userService->firstOrCreate($userDTO);

        $this->createSocialProfile($socialUser, $driver, $user);
        $this->createAccessToken($socialUser, $clientId, (string) $user->id);

        return $this;
    }

    /**
     * @throws Throwable
     */
    private function createSocialProfile(SocialiteUser $socialUser, string $driver, User $user): void
    {
        // Привязка социального профиля возможна только для авторизованных пользователей или нового пользователя
        throw_if(!$user->wasRecentlyCreated || Auth::check(), AuthorizationException::class);

        $socialProfile = new UserSocialProfile();
        $socialProfile->fill(
            UserSocialProfileDTO::factory()
                ->fromSocialUser($socialUser, $driver)
                ->toArray()
        );
        $socialProfile->user()->associate($user);
        $socialProfile->save();
    }

    private function createAccessToken(SocialiteUser $socialUser, string $clientId, string $userId): void
    {
        $this->tokenRepository->create([
            'id' => $socialUser->token,
            'client_id' => $clientId,
            'user_id' => $userId,
            'name' => 'Social authorize token',
            'revoked' => false,
            'expires_at' => Carbon::now()->addSeconds($socialUser->expiresIn),
        ]);
    }

    public function revokeAccessToken(string $tokenId): void
    {
        $this->tokenRepository->revokeAccessToken($tokenId);
    }
}
