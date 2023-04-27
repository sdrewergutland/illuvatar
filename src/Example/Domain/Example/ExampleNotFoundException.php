<?php

declare(strict_types=1);

namespace App\Example\Domain\Example;

use App\Shared\Domain\Aggregate\Exception\DomainEntityNotFoundException;

final class ExampleNotFoundException extends DomainEntityNotFoundException
{
    public static function fromId(ExampleId $id): self
    {
        return new self(sprintf('Example with id %s not found', $id->__toString()));
    }
}
