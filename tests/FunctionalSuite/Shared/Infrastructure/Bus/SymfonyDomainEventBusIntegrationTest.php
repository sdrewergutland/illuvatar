<?php

namespace App\Tests\FunctionalSuite\Shared\Infrastructure\Bus;

use App\Example\Application\EventSubscriber\DoAnotherThingOnExampleCreated;
use App\Example\Application\EventSubscriber\DoOneThingOnExampleCreated;
use App\Example\Domain\Example\Event\ExampleCreatedEvent;
use App\Tests\Library\FunctionalTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class SymfonyDomainEventBusIntegrationTest extends FunctionalTestCase
{
    /**
     * @var array<string, array<array<string>>>
     */
    private array $mapping;

    protected function setUp(): void
    {
        $command = self::getContainer()->get('console.command.messenger_debug');
        assert(is_object($command));
        $r = new \ReflectionObject($command);
        $p = $r->getProperty('mapping');
        $p->setAccessible(true);
        // @phpstan-ignore-next-line
        $this->mapping = $p->getValue($command);
        $p->setAccessible(false);
    }

    /**
     * @test
     */
    public function handlersCountIsNotChanged(): void
    {
        $this->assertCount(1, $this->mapping['domain.event.bus']);
    }

    /**
     * @test
     *
     * @param string[] $expectedHandlers
     *
     * @dataProvider messages
     */
    public function allIsRightlyRegistered(string $bus, string $messageIdentifier, array $expectedHandlers): void
    {
        $isCorrectlySubscribed = false;
        foreach ($this->mapping[$bus] as $messageKey => $messageConfiguration) {
            $registeredHandlers = array_map(
                fn ($config): string => $config[0],
                $messageConfiguration
            );
            if ($messageKey === $messageIdentifier) {
                $isCorrectlySubscribed = true;
                foreach ($expectedHandlers as $expectedHandler) {
                    $this->assertContains($expectedHandler, $registeredHandlers);
                }
            }
        }

        $this->assertTrue($isCorrectlySubscribed, sprintf('Event %s is not registered in bus %s', $messageIdentifier, $bus));
    }

    public function messages(): \Generator
    {
        yield [
            'bus'     => 'domain.event.bus',
            'message' => ExampleCreatedEvent::class,
            'handler' => [
                DoAnotherThingOnExampleCreated::class,
                DoOneThingOnExampleCreated::class,
            ],
        ];
    }
}
