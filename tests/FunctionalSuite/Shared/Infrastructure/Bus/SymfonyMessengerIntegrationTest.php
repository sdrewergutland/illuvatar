<?php

namespace App\Tests\FunctionalSuite\Shared\Infrastructure\Bus;

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\Heartbeat\HeartbeatCommand;
use App\Tests\Library\Extension\MessengerAwareTestTrait;
use App\Tests\Library\Extension\SetupAwareTrait;
use App\Tests\Library\FunctionalTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class SymfonyMessengerIntegrationTest extends FunctionalTestCase
{
    use MessengerAwareTestTrait;
    use SetupAwareTrait;

    private CommandBusInterface $commandBus;

    protected function setUp(): void
    {
        $commandBus = self::getContainer()->get(CommandBusInterface::class);
        assert($commandBus instanceof CommandBusInterface, 'CommandBus is not available');
        $this->commandBus = $commandBus;
    }

    /**
     * @test
     */
    public function itRoutesAndHandlesHeartbeatCommandsAsynchronously(): void
    {
        $this->testCaseSetup();

        $command = new HeartbeatCommand('Just a test');
        $this->commandBus->dispatch($command);
        $this->assertFalse(HeartbeatCommand::$isHandled);
        $this->messengerRunWorkerOnDefaultForOneMessage(self::$kernel);
        $this->assertTrue(HeartbeatCommand::$isHandled);
    }
}
