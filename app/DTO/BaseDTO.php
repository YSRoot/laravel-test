<?php

namespace App\DTO;

use App\Enums\CaseEnum;
use Illuminate\Support\Str;
use Spatie\DataTransferObject\DataTransferObject;

abstract class BaseDTO extends DataTransferObject
{
    public int $case = CaseEnum::SNAKE;

    public function toArray(): array
    {
        $items = parent::toArray();

        foreach ($items as $key => $item) {
            if (is_object($item) && method_exists($item, 'toArray')) {
                $items[$key] = $item->toArray();
            }
        }

        return $this->toCase($items);
    }

    public function all(): array
    {
        return $this->toCase(parent::all());
    }

    private function toCase(array $array): array
    {
        $snake = [];

        $case = match ($this->case) {
            CaseEnum::CAMEL => 'camel',
            CaseEnum::KEBAB => 'kebab',
            default => 'snake'
        };

        foreach ($array as $key => $value) {
            $snake[Str::{$case}((string)$key)] = $value;
        }

        return $snake;
    }
}
