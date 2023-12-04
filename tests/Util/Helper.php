<?php

declare(strict_types=1);

namespace App\Tests\Util;

use Safe\DateTimeImmutable;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use function Safe\class_uses;

/**
 * @method static void assertContains($needle, iterable $haystack, string $message = '')
 * @method static void assertEmpty($actual, string $message = '')
 * @method static void assertEquals($expected, $actual, string $message = '')
 * @method static void assertEqualsCanonicalizing($expected, $actual, string $message = '')
 * @method static void assertInstanceOf(string $expected, $actual, string $message = '')
 * @method static void assertJson(string $actualJson, string $message = '')
 * @method static void assertLessThan($expected, $actual, string $message = '')
 * @method static void assertMatchesRegularExpression(string $pattern, string $string, string $message = '')
 * @method static void assertNotEmpty($actual, string $message = '')
 * @method static void assertSame($expected, $actual, string $message = '')
 * @method static void assertTrue($condition, string $message = '')
 * @method static void fail(string $message = '')
 * @method        void expectException(string $exception)
 */
trait Helper
{
    use FakerTrait;

    final protected static function assertSameId(Ulid $expected, Ulid $other): void
    {
        self::assertTrue(
            $expected->equals($other),
            sprintf('Expected %s to be equal to %s', $expected, $other),
        );
    }

    final protected static function assertDateTimeImmutableIsNow(\DateTimeInterface $date, int $epsilon = 2): void
    {
        $now = new DateTimeImmutable('now');

        self::assertLessThan($epsilon, abs($date->getTimestamp() - $now->getTimestamp()), sprintf(
            'Failed asserting that object implementing %s is within an epsilon of %d seconds of the current time.',
            \DateTimeInterface::class,
            $epsilon,
        ));
    }

    final protected static function assertStringIsUuidString(string $value): void
    {
        $pattern = '/^[0-9a-f]{8}\-[0-9a-f]{4}\-[0-9a-f]{4}\-[0-9a-f]{4}\-[0-9a-f]{12}$/i';

        self::assertMatchesRegularExpression($pattern, $value, sprintf(
            'Failed asserting that value "%s" is a UUID.',
            $value,
        ));
    }

    /**
     * @param array<string, array<mixed>|float|int|string> $expectedQueryParameters
     */
    final protected static function assertStringEqualsQueryBuiltFromParameters(array $expectedQueryParameters, string $query): void
    {
        parse_str($query, $actualParameters);

        self::assertEquals($expectedQueryParameters, $actualParameters, sprintf(
            'Failed asserting that the query string "%s" equals a query constructed from the expected parameters.',
            $query,
        ));
    }

    /**
     * @param ConstraintViolationListInterface<ConstraintViolationInterface> $violations
     * @param string[]                                                       $expectedMessages
     */
    final protected static function assertViolationsForPropertyPath(
        string $propertyPath,
        ConstraintViolationListInterface $violations,
        array $expectedMessages = [],
    ): void {
        self::assertNotEmpty($violations, 'Failed asserting that a violation has occurred.');

        $violationsForPropertyPath = array_filter(iterator_to_array($violations), static fn (ConstraintViolationInterface $violation): bool => $violation->getPropertyPath() === $propertyPath);

        self::assertNotEmpty($violationsForPropertyPath, sprintf(
            'Failed asserting that a violation for property path "%s" has occurred.',
            $propertyPath,
        ));

        if ([] === $expectedMessages) {
            return;
        }

        $actualMessages = array_map(static fn (ConstraintViolationInterface $violation): string => (string) $violation->getMessage(), $violationsForPropertyPath);

        self::assertEqualsCanonicalizing($expectedMessages, $actualMessages);
    }

    /**
     * @param ConstraintViolationListInterface<ConstraintViolationInterface> $violations
     * @param string[]                                                       $expectedMessages
     */
    final protected static function assertViolationsForPropertyPathWithPrefix(
        string $propertyPathPrefix,
        ConstraintViolationListInterface $violations,
        array $expectedMessages = [],
    ): void {
        self::assertNotEmpty($violations, 'Failed asserting that a violation has occurred.');

        $violationsForPropertyPathWithPrefix = array_filter(iterator_to_array($violations), static fn (ConstraintViolationInterface $violation): bool => str_starts_with($violation->getPropertyPath(), $propertyPathPrefix));

        self::assertNotEmpty($violationsForPropertyPathWithPrefix, sprintf(
            'Failed asserting that a violation for property path with prefix "%s" has occurred.',
            $propertyPathPrefix,
        ));

        if ([] === $expectedMessages) {
            return;
        }

        $actualMessages = array_map(static fn (ConstraintViolationInterface $violation): string => (string) $violation->getMessage(), $violationsForPropertyPathWithPrefix);

        self::assertEqualsCanonicalizing($expectedMessages, $actualMessages);
    }

    /**
     * @param ConstraintViolationListInterface<ConstraintViolationInterface> $violations
     */
    final protected static function assertNoViolationsForPropertyPath(
        string $propertyPath,
        ConstraintViolationListInterface $violations,
    ): void {
        $violationsForPropertyPath = array_filter(iterator_to_array($violations), static fn (ConstraintViolationInterface $violation): bool => $violation->getPropertyPath() === $propertyPath);

        $actualMessages = array_map(static fn (ConstraintViolationInterface $violation): string => (string) $violation->getMessage(), $violationsForPropertyPath);

        self::assertEmpty($actualMessages, sprintf(
            <<<'TXT'
Failed asserting that no violations for property path "%s" have occurred.

Found the following violation messages for property path "%s":

%s

TXT,
            $propertyPath,
            $propertyPath,
            self::listItems(...$actualMessages),
        ));
    }

    /**
     * @param ConstraintViolationListInterface<ConstraintViolationInterface> $violations
     */
    final protected static function assertNoViolationsForPropertyPathWithPrefix(
        string $propertyPathPrefix,
        ConstraintViolationListInterface $violations,
    ): void {
        $violationsForPropertyPathWithPrefix = array_filter(iterator_to_array($violations), static fn (ConstraintViolationInterface $violation): bool => str_starts_with($violation->getPropertyPath(), $propertyPathPrefix));

        $actualMessages = array_map(static fn (ConstraintViolationInterface $violation): string => sprintf(
            '%s: %s',
            $violation->getPropertyPath(),
            (string) $violation->getMessage(),
        ), $violationsForPropertyPathWithPrefix);

        sort($actualMessages);

        self::assertEmpty($actualMessages, sprintf(
            <<<'TXT'
Failed asserting that no violations for property path with prefix "%s" have occurred.

Found the following violation messages for property path(s) with prefix "%s":

%s

TXT,
            $propertyPathPrefix,
            $propertyPathPrefix,
            self::listItems(...$actualMessages),
        ));
    }

    final protected static function listItems(string ...$items): string
    {
        $bullet = ' - ';

        $list = implode(
            sprintf(
                "\n%s",
                $bullet,
            ),
            $items,
        );

        return <<<TXT
{$bullet}{$list}
TXT;
    }

    final protected static function assertSameDateTime(\DateTimeInterface $expected, ?\DateTimeInterface $actual): void
    {
        if (null === $actual) {
            self::fail(sprintf(
                'Failed asserting that null equals DateTime "%s".',
                $expected->format('Y-m-d H:i:s'),
            ));
        } else {
            self::assertSame($expected->format('U'), $actual->format('U'));
        }
    }

    /**
     * @phpstan-param class-string $exceptionClassName
     */
    final protected static function failAssertingThatExceptionWasThrown(string $exceptionClassName): void
    {
        self::fail(sprintf(
            'Failed asserting that "%s" exception was thrown.',
            $exceptionClassName,
        ));
    }

    final protected static function assertClassUsesTrait(string $traitName, string $className): void
    {
        self::assertContains($traitName, class_uses($className), sprintf(
            'Failed asserting that "%s" uses trait "%s".',
            $className,
            $traitName,
        ));
    }
}
