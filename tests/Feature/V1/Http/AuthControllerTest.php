<?php

namespace Tests\Feature\V1\Http;

use App\Events\UserRegistered;
use App\Models\User;
use App\Versions\V1\Http\Controllers\Auth\AuthController;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;

    private const PASSWORD = 'password';

    private Client $client;
    private User $user;

    private array $connectionsToTransact = ['mysql'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Client::factory()->asPasswordClient()->create();
        $this->user = User::factory()->state(['password' => bcrypt(self::PASSWORD)])->create();
    }

    /** @test */
    public function successRegister(): void
    {
        Event::fake();
        $registerParams = [
            'name' => $name = $this->faker->name(),
            'email' => $email = $this->faker->email(),
            'password' => $password = $this->faker->password(8, 128),
            'password_confirmation' => $password,
        ];

        $this
            ->postJson(action([AuthController::class, 'register'], $registerParams))
            ->assertNoContent(Response::HTTP_CREATED);

        Event::assertDispatched(UserRegistered::class);
        $this->assertDatabaseHas(User::class, [
            'email' => $email,
            'name' => $name,
        ]);
    }

    public function registerValidationFailedDataProvider(): array
    {
        return [
            [
                'params' => [
                    'name' => 'name',
                    'email' => 'wrongEmail',
                    'password' => $truePassword = 'avcasd123Asd',
                ],
                'expectedErrors' => [
                    'email',
                    'password',
                ],
            ],
            [
                'params' => [
                    'name' => 'name',
                    'email' => $trueEmail = 'test@test.ru',
                    'password' => $truePassword,
                ],
                'expectedErrors' => [
                    'password',
                ],
            ],
            [
                'params' => [
                    'name' => 'name',
                    'password' => $truePassword,
                    'password_confirmation' => $truePassword,
                ],
                'expectedErrors' => [
                    'email',
                ],
            ],
            [
                'params' => [
                    'email' => $trueEmail,
                    'password' => $truePassword,
                    'password_confirmation' => $truePassword,
                ],
                'expectedErrors' => [
                    'name',
                ],
            ],
            [
                'params' => [],
                'expectedErrors' => [
                    'name',
                    'email',
                    'password',
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider registerValidationFailedDataProvider
     */
    public function failedOnValidationRegister(array $params, array $expectedErrors)
    {
        $this
            ->postJson(action([AuthController::class, 'register'], $params))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors($expectedErrors);
    }

    /** @test */
    public function failedOnUniqueEmailRegister()
    {
        $registerParams = [
            'name' => $this->faker->name(),
            'email' => $this->user->email,
            'password' => $password = $this->faker->password(8, 128),
            'password_confirmation' => $password,
        ];

        $this
            ->postJson(action([AuthController::class, 'register'], $registerParams))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email'])
            ->assertJsonFragment([
                'errors' => [
                    'email' => [
                        __('validation.unique', ['attribute' => 'email']),
                    ],
                ],
            ]);
    }

    /** @test */
    public function successLogin(): void
    {
        $loginParams = [
            'email' => $this->user->email,
            'password' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
        ];

        $this
            ->postJson(action([AuthController::class, 'login'], $loginParams))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'token_type',
                    'expires_in',
                    'access_token',
                    'refresh_token',
                ],
            ]);
    }

    /** @test */
    public function failedLoginByWrongClient(): void
    {
        $loginParams = [
            'email' => $this->user->email,
            'password' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => Str::random(),
        ];

        $this
            ->postJson(action([AuthController::class, 'login'], $loginParams))
            ->assertUnauthorized()
            ->assertJson([
                'error' => 'invalid_client',
                'error_description' => 'Client authentication failed',
                'message' => 'Client authentication failed',
            ]);
    }

    /** @test */
    public function failedLoginByWrongPassword(): void
    {
        $loginParams = [
            'email' => $this->user->email,
            'password' => Str::random(),
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
        ];

        $this
            ->postJson(action([AuthController::class, 'login'], $loginParams))
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'error' => 'invalid_grant',
                'error_description' => 'The user credentials were incorrect.',
                'message' => 'The user credentials were incorrect.',
            ]);
    }

    /** @test */
    public function successLogout(): void
    {
        $this
            ->actingAs($this->user)
            ->postJson(action([AuthController::class, 'logout']))
            ->assertNoContent();
    }

    /** @test */
    public function failedLogoutByUnauthenticated(): void
    {
        $this
            ->postJson(action([AuthController::class, 'logout']))
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    /** @test */
    public function successRefreshToken(): void
    {
        $loginParams = [
            'email' => $this->user->email,
            'password' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
        ];
        $refreshToken = $this
            ->postJson(action([AuthController::class, 'login'], $loginParams))
            ->json('data.refresh_token');
        $refreshParams = array_merge(
            ['refresh_token' => $refreshToken],
            Arr::only($loginParams, ['client_id', 'client_secret']),
        );

        $this
            ->postJson(action([AuthController::class, 'refresh'], $refreshParams))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'token_type',
                    'expires_in',
                    'access_token',
                    'refresh_token',
                ],
            ]);
    }

    /** @test */
    public function failedRefreshTokenByWrongToken(): void
    {
        $refreshParams = [
            'refresh_token' => Str::random(),
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
        ];

        $this
            ->postJson(action([AuthController::class, 'refresh'], $refreshParams))
            ->assertUnauthorized()
            ->assertJson([
                'error' => 'invalid_request',
                'error_description' => 'The refresh token is invalid.',
                'hint' => 'Cannot decrypt the refresh token',
                'message' => 'The refresh token is invalid.',
            ]);
    }
}
