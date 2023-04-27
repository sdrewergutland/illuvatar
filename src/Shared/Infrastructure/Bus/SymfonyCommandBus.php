<?php

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Command\Command;
use App\Shared\Application\Command\CommandBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class SymfonyCommandBus implements CommandBusInterface
{
    public function __construct(
        private MessageBusInterface $commandBus,
    ) {
    }

    public function dispatch(Command|array $domainEvents): void
    {
        if (!is_array($domainEvents)) {
            $domainEvents = [$domainEvents];
        }

        foreach ($domainEvents as $command) {
            $this->commandBus->dispatch($command);
        }
    }
}
