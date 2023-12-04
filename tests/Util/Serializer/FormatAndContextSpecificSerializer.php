<?php

declare(strict_types=1);

namespace App\Tests\Util\Serializer;

use Symfony\Component\Serializer\SerializerInterface;

final readonly class FormatAndContextSpecificSerializer
{
    public function __construct(
        private SerializerInterface $serializer,
        private string $format,
        private array $context = [],
    ) {
    }

    public function normalize($object)
    {
        return $this->serializer->normalize(
            $object,
            $this->format,
            $this->context,
        );
    }

    public function serialize($data)
    {
        return $this->serializer->serialize(
            $data,
            $this->format,
            $this->context,
        );
    }
}
