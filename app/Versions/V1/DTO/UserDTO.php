<?php

namespace App\Versions\V1\DTO;

use App\DTO\BaseDTO;
use App\Versions\V1\DTO\Traits\HasFactory;
use App\Versions\V1\Factories\DTO\UserDTOFactory;

/** @method static UserDTOFactory factory() */
class UserDTO extends BaseDTO
{
    use HasFactory;

    public static string $factory = UserDTOFactory::class;

    public string $name;
    public string $email;
    public string $password;
}
