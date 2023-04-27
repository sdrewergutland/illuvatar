<?php

namespace App\Example\Application\EventSubscriber;

use App\Example\Domain\Example\Event\ExampleCreatedEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'domain.event.bus', priority: 10)]
class DoAnotherThingOnExampleCreated
{
    /**
     * @SuppressWarnings("unused")
     */
    public function __invoke(ExampleCreatedEvent $exampleCreatedEvent): void
    {
        // Do another thing after doing one thing
    }
}
