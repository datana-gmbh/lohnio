<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use PHPUnit\Framework\TestCase;

final class AcceptanceDummyTest extends TestCase
{
    /**
     * @test
     */
    public function dummy(): void
    {
        self::assertTrue(true);
    }
}
