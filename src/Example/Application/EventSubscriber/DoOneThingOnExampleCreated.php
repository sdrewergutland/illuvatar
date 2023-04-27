<?php

namespace App\Example\Application\EventSubscriber;

use App\Example\Domain\Example\Event\ExampleCreatedEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'domain.event.bus', priority: 20)]
class DoOneThingOnExampleCreated
{
    /**
     * @SuppressWarnings("unused")
     */
    public function __invoke(ExampleCreatedEvent $exampleCreatedEvent): void
    {
        // Do one thing before doing another thing
    }
}
