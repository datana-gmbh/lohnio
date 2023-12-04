<?php

declare(strict_types=1);

namespace App\Tests\Util\DataProvider;

use App\Tests\Util\Helper;

final class DateTimeProvider
{
    use Helper;

    /**
     * @return \Generator<string, array{0: \DateTime}>
     */
    public static function arbitrary(): \Generator
    {
        yield 'arbitrary-date-time' => [self::faker()->dateTime()];
    }
}
