<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Util\FakerTrait;
use Behat\Mink\Exception\ExpectationException;
use Symfony\Component\HttpKernel\DataCollector\RequestDataCollector;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Webmozart\Assert\Assert;
use Zenstruck\Browser\KernelBrowser;
use function Safe\json_decode;
use function Symfony\Component\String\u;

final class AppBrowser extends KernelBrowser
{
    use FakerTrait;

    public function actingAsMandantencockpitUser(): self
    {
        $this->actingAs(new InMemoryUser('mandantencockpit', 'foobar', ['ROLE_USER']), 'api');

        return $this;
    }

    public function visitWithQueryParameters(string $url, array $queryParameters = []): self
    {
        if (u($url)->endsWith('?')) {
            $url = u($url)->trim('?')->toString();
        }

        if (u($url)->containsAny('?')) {
            throw new \InvalidArgumentException(sprintf('URL "%s" contains already query parameters.', $url));
        }

        if ([] !== $queryParameters) {
            $url .= '?'.http_build_query($queryParameters);
        }

        $this->visit($url);

        return $this;
    }

    public function assertOnWidgetPath(string $expected): self
    {
        \Zenstruck\Assert::run(fn (): bool => $this->session()->getCurrentUrl() === $expected);

        return $this;
    }

    public function assertSessionKeyEquals(string $key, int|string $expected): self
    {
        $this->assertSessionHasKey($key);

        $this->use(static function (RequestDataCollector $collector) use ($key, $expected): void {
            Assert::eq(
                $collector->getSessionAttributes()[$key],
                $expected,
                sprintf(
                    'Failed asserting that session attribute "%s" with value %s is equal to expected %s.',
                    $key,
                    $collector->getSessionAttributes()[$key],
                    $expected,
                ),
            );
        });

        return $this;
    }

    public function assertSessionHasKey(string $key): self
    {
        $this->use(static function (RequestDataCollector $collector) use ($key): void {
            Assert::keyExists(
                $collector->getSessionAttributes(),
                $key,
                sprintf('Session Attribute "%s" was not found.', $key),
            );
        });

        return $this;
    }

    public function assertSessionNotHasKey(string $key): self
    {
        $this->use(static function (RequestDataCollector $collector) use ($key): void {
            Assert::keyNotExists(
                $collector->getSessionAttributes(),
                $key,
                sprintf('Session Attribute "%s" was found which should not.', $key),
            );
        });

        return $this;
    }

    public function assertResponseMatches(string $regex): self
    {
        $this->session()->assert()->pageTextMatches($regex);

        return $this;
    }

    public function selectFieldOptionByEnum(string $selectField, \BackedEnum $enum): self
    {
        return $this->selectFieldOption($selectField, (string) $enum->value);
    }

    public function assertSeeSuccessFlashmessage(string $text): self
    {
        $this->session()->assert()->elementExists('xpath', sprintf('//div[contains(@class, "flash-message")]/div[contains(@class, "bg-green")]//p[normalize-space()="%s"]', $text));

        return $this;
    }

    public function assertSeeErrorFlashmessage(string $text): self
    {
        $this->session()->assert()->elementExists('xpath', sprintf('//div[contains(@class, "flash-message")]/div[contains(@class, "bg-red")]//p[normalize-space()="%s"]', $text));

        return $this;
    }

    public function loginAs(string $email, string $password): self
    {
        return $this
            ->visit('/login')
            ->fillField('E-Mail', $email)
            ->fillField('Passwort', $password)
            ->click('Weiter');
    }

    public function clickSpeichernUndWeiter(): self
    {
        return $this
            ->click('Speichern und weiter');
    }

    public function assertJsonLd(): self
    {
        $this->assertHeaderEquals('Content-Type', 'application/ld+json; charset=utf-8');

        return $this;
    }

    public function assertJsonNodeInCollectionEquals(int $position, string $key, mixed $expected): self
    {
        $content = json_decode((string) $this->session()->client()->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        if ($content[$position][$key] !== $expected) {
            throw new ExpectationException(
                sprintf('Could not find Json Node %s with value %s', $key, $expected),
                $this->session()->getDriver(),
            );
        }

        return $this;
    }

    public function assertJsonLdCollectionItemCount(int $count): self
    {
        return $this->assertJsonMatches('"hydra:totalItems"', $count);
    }

    /**
     * @param string $expression JMESPath expression
     */
    public function assertJsonMatchesUsingEquals(string $expression, mixed $expected): self
    {
        \Zenstruck\Assert::that($this->json()->search($expression))->equals($expected);

        return $this;
    }
}
