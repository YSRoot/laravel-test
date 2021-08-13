<?php

namespace Tests\Feature\V1\Http\Controllers;

use App\Versions\V1\Bridge\Socialite;
use App\Versions\V1\DTO\Auth\PasswordTokenDTO;
use App\Versions\V1\Http\Controllers\Auth\SocialiteController;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Tests\Feature\TestCase;

class SocialiteControllerTest extends TestCase
{
    /**
     * @dataProvider redirectDataProvider
     */
    public function testRedirectSuccess(string $driver, string $expectedRedirectUri): void
    {
        config(["services.{ $driver }.client_id" => 'clientID']);
        $requestParams = [
            'driver' => $driver,
            'client_id' => '123',
            'client_secret' => '321',
            'scope' => '*',
        ];

        $response = $this
            ->get(action([SocialiteController::class, 'redirect'], $requestParams))
            ->assertRedirect();

        $redirectPath = explode('?', app('url')->to($response->headers->get('Location')))[0];
        $this->assertEquals(app('url')->to($expectedRedirectUri), $redirectPath);
    }

    public function redirectDataProvider(): array
    {
        return [
            [
                'driver' => 'google',
                'expectedRedirectUri' => 'https://accounts.google.com/o/oauth2/auth',
            ],
        ];
    }

    /**
     * @throws UnknownProperties
     */
    public function testCallbackSuccess(): void
    {
        $tokenDto = PasswordTokenDTO::factory()->fromArray([
            'token_type' => 'Bearer',
            'expires_in' => '123',
            'access_token' => '',
            'refresh_token' => '',
        ]);
        $this->mock(Socialite::class)
            ->shouldReceive('callback')
            ->once()
            ->andReturn($tokenDto);

        $this->getJson(action([SocialiteController::class, 'callback'], ['driver' => 'google']))
            ->assertSuccessful()
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
