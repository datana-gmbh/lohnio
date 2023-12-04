<?php

declare(strict_types=1);

namespace App\Tests\Util;

use App\Bridge\Faker\ExtendedGenerator;

trait FakerTrait
{
    final protected static function faker(string $locale = 'de_DE'): ExtendedGenerator
    {
        static $fakers = [];

        if (!\array_key_exists($locale, $fakers)) {
            $fakers[$locale] = new ExtendedGenerator();
        }

        return $fakers[$locale];
    }
}
