<?php

namespace App\Shared\Application\Command;

interface CommandBusInterface
{
    /**
     * @param Command|Command[] $domainEvents
     */
    public function dispatch(Command|array $domainEvents): void;
}
