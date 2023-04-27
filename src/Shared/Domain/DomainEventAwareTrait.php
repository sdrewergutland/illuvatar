<?php

namespace App\Shared\Domain;

trait DomainEventAwareTrait
{
    private array $domainEvents = [];

    /**
     * @return DomainEvent[]
     */
    public function releaseDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    public function recordDomainEvent(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    /**
     * @return DomainEvent[]
     */
    public function readDomainEvents(): array
    {
        return $this->domainEvents;
    }
}
