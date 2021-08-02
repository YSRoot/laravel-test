<?php

namespace Tests\Feature\V1\Http;

use App\Models\User;
use App\Versions\V1\Http\Controllers\Auth\AuthController;
use Laravel\Passport\Client;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Client::factory()->asPasswordClient()->create();
    }

    /** @test */
    public function successLogin(): void
    {
        /** @var User $user */
        $user = User::factory()->state(['password' => bcrypt('password')])->create();
        $loginParams = [
            'email' => $user->email,
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
}
