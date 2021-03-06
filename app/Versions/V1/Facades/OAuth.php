<?php

namespace App\Versions\V1\Facades;

use App\Versions\V1\DTO\Contracts\OAuthAuthorizeContract;
use App\Versions\V1\Services\Auth\OAuthManagers\TokenManagerInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array make(OAuthAuthorizeContract $dto)
 * @method static TokenManagerInterface driver(string $driver)
 */
class OAuth extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'oauth';
    }
}
