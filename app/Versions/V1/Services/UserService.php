<?php

namespace App\Versions\V1\Services;

use App\Models\User;
use App\Versions\V1\DTO\UserDTO;
use Illuminate\Auth\Events\Registered;

class UserService
{
    public function register(UserDTO $userDTO): User
    {
        $user = new User();
        $user->fill($userDTO->toArray());
        $user->save();

        event(new Registered($user));

        return $user;
    }
}
