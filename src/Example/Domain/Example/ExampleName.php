<?php

namespace App\Example\Domain\Example;

use App\Shared\Domain\ValueObject\StringValueObject;
use Webmozart\Assert\Assert;

final readonly class ExampleName extends StringValueObject
{
    public static function create(string $value): self
    {
        Assert::minLength($value, 3, 'Example name must be at least 3 characters long');
        Assert::maxLength($value, 255, 'Example name must be at most 255 characters long');

        return new self($value);
    }
}
