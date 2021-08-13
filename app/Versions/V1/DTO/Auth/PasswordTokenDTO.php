<?php

namespace App\Versions\V1\DTO\Auth;

use App\DTO\BaseDTO;
use App\Versions\V1\DTO\Traits\HasFactory;
use App\Versions\V1\Factories\DTO\Auth\PasswordTokenDTOFactory;

/**
 * @method static PasswordTokenDTOFactory factory()
 */
class PasswordTokenDTO extends BaseDTO
{
    use HasFactory;

    public static string $factory = PasswordTokenDTOFactory::class;

    public string $tokenType;
    public int $expiresIn;
    public string $accessToken;
    public string $refreshToken;
}
