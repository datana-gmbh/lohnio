<?php

declare(strict_types=1);

namespace App\Tests\Integration;

final class IntegrationDummyTest extends IntegrationTestCase
{
    /**
     * @test
     */
    public function dummy(): void
    {
        self::assertTrue(true);
    }
}
