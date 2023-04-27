<?php

namespace App\Example\Application;

use App\Example\Application\UseCase\CreateExample\CreateExampleCommand;
use App\Example\Application\UseCase\CreateExample\CreateExampleCommandHandler;
use App\Example\Application\UseCase\ReadExampleQuery\ReadExampleQuery;
use App\Example\Application\UseCase\ReadExampleQuery\ReadExampleQueryHandler;
use App\Example\Domain\Example\Example;
use App\Example\Domain\Example\ExampleId;

final readonly class ExampleApplication implements ExampleApplicationInterface
{
    public function __construct(
        private ReadExampleQueryHandler $readExampleQueryHandler,
        private CreateExampleCommandHandler $createExampleCommandHandler,
    ) {
    }

    public function readExample(ReadExampleQuery $query): Example
    {
        return ($this->readExampleQueryHandler)($query);
    }

    public function createExample(CreateExampleCommand $command): ExampleId
    {
        return ($this->createExampleCommandHandler)($command);
    }
}
