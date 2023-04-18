<?php

namespace App\Example\Application\UseCase\CreateExample;

use App\Shared\Application\Command\Command;

readonly class CreateExampleCommand implements Command
{
    public function __construct(
        private string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
