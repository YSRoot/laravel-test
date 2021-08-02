<?php

namespace App\Versions\V1\DTO;

use App\Versions\V1\DTO\Traits\HasFactory;
use App\Versions\V1\Factories\DTO\PasswordTokenDTOFactory;

/** @method static PasswordTokenDTOFactory factory() */
class PasswordTokenDTO
{
    use HasFactory;

    public static string $factory = PasswordTokenDTOFactory::class;

    public function __construct(
        public string $tokenType,
        public int $expiresIn,
        public string $accessToken,
        public string $refreshToken,
    ) {
    }
}
