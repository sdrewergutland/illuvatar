<?php

namespace App\Shared\Domain;

interface DomainEventBusInterface
{
    /**
     * @param DomainEvent[] $domainEvents
     */
    public function publish(DomainEvent|array $domainEvents): void;
}
