<?php

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\Event\DomainEventBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class SymfonyEventBus implements DomainEventBusInterface
{
    public function __construct(
        private MessageBusInterface $eventBus,
    ) {
    }

    /**
     * @param DomainEvent|DomainEvent[] $domainEvents
     */
    public function publish(DomainEvent|array $domainEvents): void
    {
        if (!is_array($domainEvents)) {
            $domainEvents = [$domainEvents];
        }

        foreach ($domainEvents as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
