<?php

namespace App\Example\Application\UseCase\CreateExample;

use App\Example\Domain\Example\ExampleName;
use App\Shared\Application\Command\Command;

final readonly class CreateExampleCommand implements Command
{
    public function __construct(
        private ExampleName $name,
    ) {
    }

    public function getName(): ExampleName
    {
        return $this->name;
    }
}
