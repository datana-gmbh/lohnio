<?php

declare(strict_types=1);

namespace App\Tests\Util\DataProvider;

use App\Tests\Util\Helper;
use function Safe\fopen;

final class GenericProvider
{
    use Helper;

    /**
     * @return \Generator<array{0: mixed}>
     */
    public static function allKindsOfValuesWhere(\Closure $closure): \Generator
    {
        $values = array_filter(
            self::allKindsOfValues(),
            $closure,
        );

        foreach ($values as $key => $value) {
            yield $key => [$value];
        }
    }

    /**
     * @return array<string, null|array|bool|float|int|resource|\stdClass|string>
     */
    private static function allKindsOfValues(): array
    {
        $faker = self::faker();

        return [
            'array-empty' => [],
            'array-strings' => $faker->words(),
            'bool-false' => false,
            'bool-true' => true,
            'float' => $faker->randomFloat(2, 1.23),
            'int-greater-than-zero' => $faker->numberBetween(1),
            'int-less-than-zero' => -1 * $faker->numberBetween(1),
            'int-zero' => 0,
            'null' => null,
            'object' => new \stdClass(),
            'resource' => fopen(__FILE__, 'rb'),
            'string' => $faker->sentence(),
        ];
    }
}
