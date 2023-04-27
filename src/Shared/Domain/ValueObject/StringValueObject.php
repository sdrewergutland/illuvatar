<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract readonly class StringValueObject
{
    final protected function __construct(
        private string $value,
    ) {
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }
}
