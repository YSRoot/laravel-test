<?php

namespace Tests\V1\Services;

use App\Events\UserRegistered;
use App\Models\User;
use App\Versions\V1\DTO\UserDTO;
use App\Versions\V1\Services\UserService;
use Illuminate\Support\Facades\Event;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Tests\Feature\TestCase;

class UserServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    /**
     * @throws UnknownProperties
     */
    public function testFirstOrCreateReturnExistedUser(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $result = (new UserService())->firstOrCreate(UserDTO::factory()->fromUser($user));

        $this->assertTrue($result->is($user));
        Event::assertNotDispatched(UserRegistered::class);
    }

    /**
     * @throws UnknownProperties
     */
    public function testFirstOrCreateReturnNewUser(): void
    {
        $this->createUserTest();
    }

    /**
     * @throws UnknownProperties
     */
    public function testCreateSuccess(): void
    {
        $this->createUserTest();
    }

    /**
     * Пока firstOrCreate или просто сreate работают одинакого в случае если пользователь не нашелся
     *
     * @throws UnknownProperties
     */
    private function createUserTest(): void
    {
        /** @var User $user */
        $user = User::factory()->make();

        $result = (new UserService())->firstOrCreate(UserDTO::factory()->fromUser($user));

        $this->assertTrue($result->wasRecentlyCreated);
        $this->assertEquals($user->only('name', 'email'), $result->only(['name', 'email']));
        Event::assertDispatched(UserRegistered::class);
    }
}
