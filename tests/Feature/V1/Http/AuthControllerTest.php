<?php

namespace Tests\Feature\V1\Http;

use App\Models\User;
use App\Versions\V1\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    private const PASSWORD = 'password';

    private Client $client;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Client::factory()->asPasswordClient()->create();
        $this->user = User::factory()->state(['password' => bcrypt(self::PASSWORD)])->create();
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

        $this->postJson(action([AuthController::class, 'login'], $loginParams))
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

        $this->postJson(action([AuthController::class, 'login'], $loginParams))
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

        $this->postJson(action([AuthController::class, 'login'], $loginParams))
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'error' => 'invalid_grant',
                'error_description' => 'The user credentials were incorrect.',
                'message' => 'The user credentials were incorrect.',
            ]);
    }
}
