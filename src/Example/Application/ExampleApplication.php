<?php

namespace App\Example\Application;

use App\Example\Application\UseCase\ReadExampleQuery\ReadExampleQuery;
use App\Example\Application\UseCase\ReadExampleQuery\ReadExampleQueryHandler;
use App\Example\Domain\Example\Example;

class ExampleApplication implements ExampleApplicationInterface
{
    public function __construct(
        private readonly ReadExampleQueryHandler $readExampleQueryHandler,
    ) {
    }

    public function readExample(ReadExampleQuery $query): Example
    {
        return ($this->readExampleQueryHandler)($query);
    }
}
