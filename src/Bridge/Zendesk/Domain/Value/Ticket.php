<?php

declare(strict_types=1);

namespace App\Bridge\Zendesk\Domain\Value;

use OskarStark\Value\TrimmedNonEmptyString;
use Safe\DateTimeImmutable;
use Symfony\Component\Uid\Ulid;
use Webmozart\Assert\Assert;

final class Ticket
{
    public readonly Ulid $id;
    public readonly string $name;
    public readonly string $email;
    public readonly string $subject;
    public readonly string $body;
    public readonly DateTimeImmutable $createdAt;

    /**
     * @param array<string, mixed> $values
     */
    private function __construct(array $values)
    {
        $this->id = new Ulid();
        $this->createdAt = new DateTimeImmutable();

        Assert::keyExists($values, 'vorname');
        $vorname = TrimmedNonEmptyString::fromString($values['vorname'])->toString();

        Assert::keyExists($values, 'nachname');
        $nachname = TrimmedNonEmptyString::fromString($values['nachname'])->toString();

        $this->name = sprintf('%s %s', $vorname, $nachname);

        Assert::keyExists($values, 'email');
        Assert::email($values['email']);
        $this->email = TrimmedNonEmptyString::fromString($values['email'])->toString();

        //        Assert::keyExists($values, 'subject');
        //        $this->subject = TrimmedNonEmptyString::fromString($values['subject'])->toString();
        $this->subject = 'Hey! There';

        Assert::keyExists($values, 'nachricht');
        $this->body = TrimmedNonEmptyString::fromString($values['nachricht'])->toString();
    }

    /**
     * @param array<string, mixed> $values
     */
    public static function fromArray(array $values): self
    {
        return new self($values);
    }
}
