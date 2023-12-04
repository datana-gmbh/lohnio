<?php

declare(strict_types=1);

namespace App\Bridge\Faker\Provider;

use Faker\Provider\Base;

final class ArrayProvider extends Base
{
    public function array(): array
    {
        return [
            'foo' => 'bar',
            'baz' => [
                'quox' => 'quux',
            ],
        ];
    }

    public function emptyArray(): array
    {
        return [];
    }

    public function arrayOrEmptyArray(): array
    {
        return $this->generator->randomElement([
            $this->array(),
            $this->emptyArray(),
        ]);
    }
}
