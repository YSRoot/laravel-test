<?php

namespace App\Versions\V1\DTO\Users;

use App\DTO\BaseDTO;
use App\Versions\V1\DTO\Traits\HasFactory;
use App\Versions\V1\Factories\DTO\UserSocialProfileDTOFactory;

/**
 * @method static UserSocialProfileDTOFactory factory()
 */
class UserSocialProfileDTO extends BaseDTO
{
    use HasFactory;

    public static string $factory = UserSocialProfileDTOFactory::class;

    public string $driver;
    public string $driverId;
    public ?string $nickname;
    public ?string $name;
    public string $email;
    public ?string $avatar;
}
