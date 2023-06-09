<?php

namespace App\Shared\Domain\Event;

interface DomainEventBusInterface
{
    /**
     * @param DomainEvent[] $domainEvents
     */
    public function publish(DomainEvent|array $domainEvents): void;
}
