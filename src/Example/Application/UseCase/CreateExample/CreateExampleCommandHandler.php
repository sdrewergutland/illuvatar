<?php

namespace App\Example\Application\UseCase\CreateExample;

use App\Shared\Application\Command\CommandHandler;

class CreateExampleCommandHandler implements CommandHandler
{
    public function __invoke(CreateExampleCommand $command): void
    {
        exit(__METHOD__);
    }
}
