<?php

namespace App\Example\Domain\Example;

use App\Shared\Domain\ValueObject;
use Webmozart\Assert\Assert;

class ExampleName implements ValueObject
{
    public function __construct(private string $value)
    {
        Assert::minLength($value, 3, 'Example name must be at least 3 characters long');
        Assert::maxLength($value, 255, 'Example name must be at most 255 characters long');
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
