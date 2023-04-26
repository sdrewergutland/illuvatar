<?php

namespace App\Shared\Application\Command;

interface CommandBusInterface
{
    /**
     * @param Command|Command[] $commands
     */
    public function dispatch(Command|array $commands): void;
}
