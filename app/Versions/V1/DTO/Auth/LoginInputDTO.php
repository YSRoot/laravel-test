<?php

namespace App\Versions\V1\DTO\Auth;

use App\DTO\BaseDTO;
use App\Versions\V1\DTO\Contracts\OAuthAuthorizeContract;
use App\Versions\V1\DTO\Traits\HasFactory;
use App\Versions\V1\Factories\DTO\Auth\LoginInputDTOFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @method static LoginInputDTOFactory factory()
 */
class LoginInputDTO extends BaseDTO implements OAuthAuthorizeContract
{
    use HasFactory;

    public static string $factory = LoginInputDTOFactory::class;

    public string $email;
    public string $password;
    public string $clientId;
    public string $clientSecret;
    public string $scope;

    #[ArrayShape([
        'username' => "string",
        'password' => "string",
        'client_id' => "string",
        'client_secret' => "string",
        'scope' => "string"
    ])]
    public function authorizeParams(): array
    {
        return [
            'username' => $this->email,
            'password' => $this->password,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => $this->scope,
        ];
    }
}
