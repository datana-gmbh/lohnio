<?php

declare(strict_types=1);

namespace App\Tests\Functional;

final class FunctionalDummyTest extends FunctionalTestCase
{
    /**
     * @test
     */
    public function dummy(): void
    {
        self::assertTrue(true);
    }
}
