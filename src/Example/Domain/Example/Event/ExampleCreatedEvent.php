<?php

declare(strict_types=1);

namespace App\Example\Domain\Example\Event;

use App\Example\Domain\Example\ExampleId;
use App\Example\Domain\Example\ExampleName;
use App\Shared\Domain\DomainEvent;

final readonly class ExampleCreatedEvent extends DomainEvent
{
    public function __construct(
        private ExampleId $id,
        private ExampleName $name,
    ) {
    }

    public function getId(): ExampleId
    {
        return $this->id;
    }

    public function getName(): ExampleName
    {
        return $this->name;
    }
}
