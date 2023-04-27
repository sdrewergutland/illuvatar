<?php

namespace App\Example\Application;

use App\Example\Application\UseCase\CreateExample\CreateExampleCommand;
use App\Example\Application\UseCase\ReadExampleQuery\ReadExampleQuery;
use App\Example\Domain\Example\Example;
use App\Example\Domain\Example\ExampleId;

interface ExampleApplicationInterface
{
    public function readExample(ReadExampleQuery $query): Example;

    public function createExample(CreateExampleCommand $command): ExampleId;
}
