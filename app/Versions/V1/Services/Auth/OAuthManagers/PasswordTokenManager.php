<?php

namespace App\Versions\V1\Services\Auth\TokenManagers;

use App\Versions\V1\DTO\OAuthAuthorizeContract;
use App\Versions\V1\Enums\GrantTypeEnum;
use League\OAuth2\Server\AuthorizationServer;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class PasswordTokenManager implements TokenManagerInterface
{
    protected static string $grantType = GrantTypeEnum::PASSWORD;

    public function make(OAuthAuthorizeContract $dto): array {
        return $this->dispatchRequestToAuthorizationServer(
            $this->createRequest($dto)
        );
    }

    private function dispatchRequestToAuthorizationServer(ServerRequestInterface $request): array
    {
        return json_decode(
            app(AuthorizationServer::class)->respondToAccessTokenRequest($request, new Response())->getBody(),
            true
        );
    }

    private function createRequest(OAuthAuthorizeContract $dto): ServerRequestInterface {
        return (new ServerRequest('POST', 'not-important'))->withParsedBody(array_merge(
            $dto->authorizeParams(),
            ['grant_type' => static::$grantType]
        ));
    }
}
