<?php

namespace App\Versions\V1\Factories;

use App\Versions\V1\DTO\LoginInputDTO;
use App\Versions\V1\Enums\GrantTypeEnum;
use League\OAuth2\Server\AuthorizationServer;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class PasswordTokenFactory
{
    public function make(LoginInputDTO $dto): array {
        return $this->dispatchRequestToAuthorizationServer(
            $this->createRequest($dto->email, $dto->password, $dto->clientId, $dto->clientSecret, $dto->scope)
        );
    }

    private function dispatchRequestToAuthorizationServer(ServerRequestInterface $request): array
    {
        return json_decode(
            app(AuthorizationServer::class)->respondToAccessTokenRequest($request, new Response())->getBody(),
            true
        );
    }

    private function createRequest(
        string $email,
        string $password,
        string $clientId,
        string $clientSecret,
        string $scope,
    ): ServerRequestInterface {
        return (new ServerRequest('POST', 'not-important'))->withParsedBody([
            'grant_type' => GrantTypeEnum::PASSWORD,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'username' => $email,
            'password' => $password,
            'scope' => $scope,
        ]);
    }
}
