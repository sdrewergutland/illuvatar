<?php

declare(strict_types=1);

namespace App\Example\Domain\Example;

use App\Shared\Domain\DomainEntityNotFoundException;
use Symfony\Component\Uid\Uuid;

final class ExampleNotFoundException extends DomainEntityNotFoundException
{
    public static function fromId(Uuid $id): self
    {
        return new self(sprintf('Example with id %s not found', $id->__toString()));
    }
}
