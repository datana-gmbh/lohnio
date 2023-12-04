<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Tests\Util\Helper;
use App\Tests\Util\Serializer\FormatAndContextSpecificSerializer;
use App\Tests\Util\TestKernel;
use Doctrine\ORM;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zenstruck\Console\Test\InteractsWithConsole;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class IntegrationTestCase extends KernelTestCase
{
    use Helper;
    use ResetDatabase;
    use Factories;
    use InteractsWithConsole;

    protected function setUp(): void
    {
        self::bootKernel([
            'debug' => false,
        ]);
    }

    final protected static function createKernel(array $options = []): KernelInterface
    {
        return TestKernel::create(
            $options,
            static::class,
            static function (ContainerBuilder $containerBuilder): void {
                static::configureContainerBuilder($containerBuilder);
            },
        );
    }

    /**
     * Override this method to configure or replace services.
     */
    protected static function configureContainerBuilder(ContainerBuilder $containerBuilder): void
    {
    }

    final protected static function formatAndContextSpecificSerializer(string $format, array $context = []): FormatAndContextSpecificSerializer
    {
        return new FormatAndContextSpecificSerializer(
            self::serializer(),
            $format,
            $context,
        );
    }

    /**
     * @return ORM\EntityManager&ORM\EntityManagerInterface
     */
    final protected static function entityManager(): EntityManagerInterface
    {
        return self::getContainer()->get(EntityManagerInterface::class);
    }

    /**
     * @return NormalizerInterface&SerializerInterface
     */
    final protected static function serializer(): SerializerInterface
    {
        return self::getContainer()->get(SerializerInterface::class);
    }

    final protected static function validator(): ValidatorInterface
    {
        return self::getContainer()->get(ValidatorInterface::class);
    }
}
