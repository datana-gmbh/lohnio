<?php

declare(strict_types=1);

namespace App\Tests\Util;

use App\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class TestKernel extends Kernel
{
    public function __construct(
        string $environment,
        bool $debug,
        private readonly string $cacheIdentifier,
        private readonly \Closure $configureContainerBuilder,
    ) {
        parent::__construct(
            $environment,
            $debug,
        );
    }

    /**
     * @phpstan-param class-string $testClassName
     */
    public static function create(array $options, string $testClassName, \Closure $configureContainerBuilder): self
    {
        return new self(
            self::environment($options),
            self::debug($options),
            self::cacheIdentifier($testClassName),
            $configureContainerBuilder,
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        parent::registerContainerConfiguration($loader);

        $configureContainerBuilder = $this->configureContainerBuilder;

        $loader->load(static function (ContainerBuilder $containerBuilder) use ($configureContainerBuilder): void {
            $configureContainerBuilder($containerBuilder);
        });
    }

    public function getCacheDir(): string
    {
        return sprintf(
            '%s/%s',
            parent::getCacheDir(),
            $this->cacheIdentifier,
        );
    }

    private static function environment(array $options): string
    {
        return $options['environment'] ?? $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'test';
    }

    private static function debug(array $options): bool
    {
        if (isset($options['debug'])) {
            return (bool) $options['debug'];
        }

        if (isset($_ENV['APP_DEBUG'])) {
            return (bool) $_ENV['APP_DEBUG'];
        }

        if (isset($_SERVER['APP_DEBUG'])) {
            return (bool) $_SERVER['APP_DEBUG'];
        }

        return true;
    }

    /**
     * The idea is to use a separate cache for each test class that configures the container builder differently.
     *
     * @see \App\Tests\Functional\AbstractTestCase::configureContainerBuilder()
     * @see \App\Tests\Integration\IntegrationTestCase::configureContainerBuilder()
     *
     * @phpstan-param class-string $testClassName
     */
    private static function cacheIdentifier(string $testClassName): string
    {
        $classReflection = new \ReflectionClass($testClassName);

        $methodReflection = $classReflection->getMethod('configureContainerBuilder');

        $declaringTestClassName = $methodReflection->getDeclaringClass()->getName();

        return sha1($declaringTestClassName);
    }
}
