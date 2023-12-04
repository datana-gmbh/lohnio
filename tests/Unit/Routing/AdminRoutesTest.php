<?php

declare(strict_types=1);

namespace App\Tests\Unit\Routing;

use App\Routing\AdminRoutes;
use App\Tests\Unit\UnitTestCase;

final class AdminRoutesTest extends UnitTestCase
{
    /**
     * @test
     */
    public function constants(): void
    {
        self::assertSame('admin_dashboard', AdminRoutes::DASHBOARD);
    }

    /**
     * @test
     */
    public function numberOfRoutes(): void
    {
        $class = new \ReflectionClass(AdminRoutes::class);

        self::assertCount(1, $class->getConstants());
    }
}
