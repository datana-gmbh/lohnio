<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Util\Helper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Webmozart\Assert\Assert;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Console\Test\InteractsWithConsole;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @method AppBrowser browser()
 */
abstract class FunctionalTestCase extends WebTestCase
{
    /**
     * https://github.com/zenstruck/browser for documentation.
     */
    use HasBrowser;

    use Helper;
    use ResetDatabase;
    use Factories;

    /**
     * https://github.com/zenstruck/console-test for documentation.
     */
    use InteractsWithConsole;

    protected function setUp(): void
    {
        $this->browser();
    }

    /**
     * @return EntityManager&EntityManagerInterface
     */
    final protected static function entityManager(): EntityManagerInterface
    {
        return static::getContainer()->get(EntityManagerInterface::class);
    }

    protected static function fixtureFile(string $filepath): string
    {
        Assert::notStartsWith($filepath, '/', 'Filepath is not allowed to start with a "/"');

        $file = __DIR__.'/../Fixture/'.$filepath;

        Assert::fileExists($file);

        return $file;
    }
}
