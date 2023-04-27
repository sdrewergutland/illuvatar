<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Symfony\Component\Uid\Uuid as SymfonyUuid;

abstract readonly class Uuid implements \Stringable, \JsonSerializable
{
    final private function __construct(
        private SymfonyUuid $value,
    ) {
    }

    final public static function random(): static
    {
        return new static(SymfonyUuid::v7());
    }

    public function __toString(): string
    {
        return $this->value->__toString();
    }

    public function equals(self $other): bool
    {
        return $this->value->equals($other->value);
    }

    public function value(): SymfonyUuid
    {
        return $this->value;
    }

    public static function fromString(string $value): static
    {
        return new static(SymfonyUuid::fromString($value));
    }

    public static function fromUuid(SymfonyUuid $value): static
    {
        return new static($value);
    }

    public function jsonSerialize(): string
    {
        return $this->value->jsonSerialize();
    }
}
