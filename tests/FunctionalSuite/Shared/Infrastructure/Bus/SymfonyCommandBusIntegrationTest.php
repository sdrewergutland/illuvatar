<?php

namespace App\Tests\FunctionalSuite\Shared\Infrastructure\Bus;

use App\Shared\Application\Command\Heartbeat\HeartbeatCommand;
use App\Shared\Application\Command\Heartbeat\HeartbeatCommandHandler;
use App\Tests\Library\FunctionalTestCase;
use Webmozart\Assert\Assert;

/**
 * @internal
 *
 * @coversNothing
 */
class SymfonyCommandBusIntegrationTest extends FunctionalTestCase
{
    /**
     * @var array<string, array<string>>
     */
    private array $mapping;

    protected function setUp(): void
    {
        $command = self::getContainer()->get('console.command.messenger_debug');
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
        $this->assertCount(1, $this->mapping['application.command.bus']);
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
            $registeredHandlers = $messageConfiguration[0] ?? [];
            Assert::isArray($registeredHandlers);
            if ($messageKey === $messageIdentifier) {
                $isCorrectlySubscribed = true;
                foreach ($expectedHandlers as $expectedHandler) {
                    $this->assertContains($expectedHandler, $registeredHandlers);
                }
            }
        }

        $this->assertTrue($isCorrectlySubscribed, sprintf('Message %s is not registered in bus %s', $messageIdentifier, $bus));
    }

    public function messages(): \Generator
    {
        yield [
            'bus'     => 'application.command.bus',
            'message' => HeartbeatCommand::class,
            'handler' => [
                HeartbeatCommandHandler::class,
            ],
        ];
    }
}
