<?php

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Command\Command;
use App\Shared\Application\Command\CommandBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyCommandBus implements CommandBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ) {
    }

    public function dispatch(Command|array $commands): void
    {
        if (!is_array($commands)) {
            $commands = [$commands];
        }

        foreach ($commands as $command) {
            $this->commandBus->dispatch($command);
        }
    }
}
