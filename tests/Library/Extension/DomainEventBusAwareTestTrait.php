<?php

namespace App\Tests\Library\Extension;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\IsEqual;
use Symfony\Component\Messenger\TraceableMessageBus;

trait DomainEventBusAwareTestTrait
{
    abstract public static function getContainer(): \Symfony\Component\DependencyInjection\ContainerInterface;

    protected static function assertDomainEventBusReceivedEvent(
        string $expectedEventClass,
        int $expectedTimes = 1
    ): void {
        $traceableDomainEventBus = self::getContainer()->get('domain.event.bus');
        \assert($traceableDomainEventBus instanceof TraceableMessageBus);

        $dispatchedMessages = $traceableDomainEventBus->getDispatchedMessages();

        $found = 0;

        foreach ($dispatchedMessages as $dispatchedMessage) {
            if ($dispatchedMessage['message'] instanceof $expectedEventClass) {
                ++$found;
            }
        }

        Assert::assertThat(
            $found,
            new IsEqual($expectedTimes),
            "The event {$expectedEventClass} was expected to be dispatched {$expectedTimes} times, but was dispatched {$found} times."
        );
    }
}
