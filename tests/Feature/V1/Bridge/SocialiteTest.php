<?php

namespace Tests\Feature\V1\Bridge;

use App\Enums\GrantTypeEnum;
use App\Versions\V1\Bridge\Socialite;
use App\Versions\V1\DTO\SocialAuthorizeDTO;
use App\Versions\V1\Facades\OAuth;
use App\Versions\V1\Http\Requests\Auth\SocialiteRedirectRequest;
use App\Versions\V1\Services\Auth\OAuthManagers\TokenManagerInterface;
use App\Versions\V1\Services\SocialiteService;
use Hamcrest\Core\IsEqual;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite as LaravelSocialite;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;
use Mockery;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Tests\Feature\TestCase;
use Throwable;

class SocialiteTest extends TestCase
{
    /**
     * @throws BindingResolutionException
     */
    public function testRedirectSuccess(): void
    {
        $driver = 'google';
        $clientParams = [
            'client_id' => 'ID',
            'client_secret' => 'secret',
            'scope' => '*',
        ];
        list($session, $request) = $this->mockRedirectRequest($clientParams);
        $socialiteService = $this->mock(SocialiteService::class);

        $result = (new Socialite($socialiteService))->redirect($request, $driver);

        $this->assertInstanceOf(RedirectResponse::class, $result);
        $this->assertTrue($session->has(Socialite::CLIENT_PARAMS_SESSION_KEY));
        $this->assertEquals(array_values($clientParams), $session->get(Socialite::CLIENT_PARAMS_SESSION_KEY));
    }

    /**
     * @dataProvider callbackDataProvider
     * @throws Throwable
     */
    public function testCallbackSuccess(array $userParams, array $requestSessionParams, array $tokenParams): void
    {
        $driver = 'google';
        $socialiteUser = (new User())->setRaw($userParams)->map($userParams);
        $this->mockSocialite($driver, $socialiteUser);
        $this->mockOAuth($driver, $socialiteUser->token, $requestSessionParams, $tokenParams);
        $request = $this->mockCallbackRequest($requestSessionParams);
        $socialiteService = $this->mock(SocialiteService::class)
            ->shouldReceive('handleCallback')
            ->once()
            ->with($socialiteUser, $driver)
            ->getMock();

        $result = (new Socialite($socialiteService))->callback($request, $driver);

        $this->assertEquals($result->toArray(), $tokenParams);
    }

    public function callbackDataProvider(): array
    {
        return [
            [
                'userParams' => [
                    'email' => 'test@test.ru',
                    'id' => '1234',
                    'name' => 'test',
                    'nickname' => 'test',
                    'token' => 'social_token',
                ],
                'requestSessionParams' => [
                    'client_id' => '123',
                    'client_secret' => '321',
                    'scope' => '*',
                ],
                'tokenParams' => [
                    'token_type' => 'Bearer',
                    'expires_in' => 123,
                    'access_token' => 'access_token',
                    'refresh_token' => 'refresh_token',
                ],
            ],
        ];
    }

    private function mockSocialite(string $driver, User $socialiteUser): void
    {
        LaravelSocialite::shouldReceive('driver')
            ->with($driver)
            ->once()
            ->andReturn(
                Mockery::mock(AbstractProvider::class)
                    ->shouldReceive('user')
                    ->withNoArgs()
                    ->once()
                    ->andReturn($socialiteUser)
                    ->getMock()
            );
    }

    /**
     * @throws UnknownProperties
     */
    private function mockOAuth(string $driver, string $token, array $requestParams, array $tokenParams): void
    {
        $socDto = SocialAuthorizeDTO::factory()->fromParams(
            $driver,
            $token,
            $requestParams['client_id'],
            $requestParams['client_secret'],
            $requestParams['scope'],
        );
        OAuth::shouldReceive('driver')
            ->once()
            ->with(GrantTypeEnum::SOCIAL)
            ->andReturn(
                Mockery::mock(TokenManagerInterface::class)
                    ->shouldReceive('make')
                    ->once()
                    ->with(IsEqual::equalTo($socDto))
                    ->andReturn($tokenParams)
                    ->getMock()
            );
    }

    /**
     * @throws BindingResolutionException
     */
    private function mockCallbackRequest(array $requestSessionParams): Request
    {
        session()->setDefaultDriver('array');
        $session = session()->driver();
        $request = Request::create('not-important');
        $request->setLaravelSession($session);
        $this->app->make('request')->setLaravelSession($session);
        $request->session()->put([Socialite::CLIENT_PARAMS_SESSION_KEY => array_values($requestSessionParams)]);

        return $request;
    }

    /**
     * @throws BindingResolutionException
     */
    private function mockRedirectRequest(array $clientParams): array
    {
        session()->setDefaultDriver('array');
        $session = session()->driver();
        $request = SocialiteRedirectRequest::create('not-important', parameters: $clientParams);
        $request->setLaravelSession($session);
        $this->app->make('request')->setLaravelSession($session);

        return array($session, $request);
    }
}
