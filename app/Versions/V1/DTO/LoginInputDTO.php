<?php

namespace App\Versions\V1\DTO;

use App\Versions\V1\DTO\Traits\HasFactory;
use App\Versions\V1\Factories\DTO\LoginInputDTOFactory;

/** @method static LoginInputDTOFactory factory() */
class LoginInputDTO
{
    use HasFactory;

    public static string $factory = LoginInputDTOFactory::class;

    public function __construct(
        public string $email,
        public string $password,
        public string $clientId,
        public string $clientSecret,
        public string $scope,
    ){
    }
}
