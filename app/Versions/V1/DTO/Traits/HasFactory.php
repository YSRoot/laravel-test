<?php

namespace App\Versions\V1\DTO\Traits;

use App\Versions\V1\Factories\DTO\Contracts\FactoryInterface;

trait HasFactory
{
    public static function factory(): FactoryInterface
    {
        return new static::$factory();
    }
}
