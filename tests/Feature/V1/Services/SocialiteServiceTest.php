<?php

namespace Tests\Feature\V1\Services;

use App\Models\UserSocialProfile;
use App\Versions\V1\Services\SocialiteService;
use App\Versions\V1\Services\UserService;
use App\Models\User as UserModel;
use Illuminate\Auth\Access\AuthorizationException;
use Laravel\Socialite\Two\User;
use Tests\Feature\TestCase;
use Throwable;

class SocialiteServiceTest extends TestCase
{
    private string $driver;
    private User $socialiteUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->driver = 'google.com';
        $email = 'test@test.ru';
        $userParams = [
            'email' => $email,
            'id' => '1234',
            'name' => 'test',
            'nickname' => 'test',
            'token' => 'social_token',
        ];
        $this->socialiteUser = (new User())
            ->setRaw($userParams)
            ->map($userParams);
    }

    /**
     * @throws Throwable
     */
    public function testCallbackCreateUser(): void
    {
        $this->getSocialiteService()->handleCallback($this->socialiteUser, $this->driver);

        $this->assertDatabaseHas(UserModel::class, [
            'email' => $this->socialiteUser->getEmail(),
        ]);
        $this->assertDatabaseHas(UserSocialProfile::class, [
            'driver' => $this->driver,
            'driver_id' => $this->socialiteUser->getId(),
            'nickname' => $this->socialiteUser->getNickname(),
            'name' => $this->socialiteUser->getName(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function testCallbackCantCreateSocialProfileForExistUser(): void
    {
        UserModel::factory()->create(['email' => $this->socialiteUser->getEmail()]);
        $this->expectException(AuthorizationException::class);

        $this->getSocialiteService()->handleCallback($this->socialiteUser, $this->driver);
    }

    /**
     * @throws Throwable
     */
    public function testCallbackCanCreateSocialProfileForExistUserIfIsAuthorized(): void
    {
        /** @var UserModel $user */
        $user = UserModel::factory()->create(['email' => $this->socialiteUser->getEmail()]);
        $this->actingAs($user);

        $this->getSocialiteService()->handleCallback($this->socialiteUser, $this->driver);

        $this->assertDatabaseHas(UserModel::class, [
            'email' => $this->socialiteUser->getEmail(),
        ]);
        $this->assertDatabaseHas(UserSocialProfile::class, [
            'driver' => $this->driver,
            'driver_id' => $this->socialiteUser->getId(),
            'nickname' => $this->socialiteUser->getNickname(),
            'name' => $this->socialiteUser->getName(),
        ]);
    }

    private function getSocialiteService(): SocialiteService
    {
        return (new SocialiteService(new UserService()));
    }
}
