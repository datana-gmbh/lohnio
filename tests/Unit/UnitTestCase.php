<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Tests\Util\FakerTrait;
use App\Tests\Util\Helper;
use PHPUnit\Framework\TestCase;
use Zenstruck\Foundry\Test\Factories;

abstract class UnitTestCase extends TestCase
{
    use Helper;
    use Factories;
    use FakerTrait;
}
